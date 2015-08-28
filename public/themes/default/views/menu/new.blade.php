@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files_vertical($submit,'POST',array('class'=>'custom addAttendeeForm'))}}

<div class="row-fluid">
    <div class="span8">
        <div class="dd" id="nestable_list">
            <?php

                $menu_array = array(
                    'links'=>array(
                                    'display' => 'Recommended Links',
                                    'sub' => array(
                                        'products' => array(
                                            'display' => 'High-Quality Products',
                                            'url' => 'links/#products'
                                        ),
                                        'services' => array(
                                            'display' => 'Helpful Services',
                                            'url' => 'links/#services',
                                            'sub' => array(
                                                'local' => array(
                                                    'display' => 'Local Services',
                                                    'url' => 'links/#services_local'
                                                ),
                                                'online' => array(
                                                    'display' => 'Online Services',
                                                    'url' => 'links/#services_online'
                                                )
                                            )
                                        )
                                    )
                                )

                    );

                $id_cnt = 0;
                $menutree = buildMenu($menu_array);
                print $menutree;
            ?>
            {{--

            <ol class="dd-list">
                <li class="dd-item" data-id="1">
                    <div class="dd-handle">Item 1</div>
                </li>
                <li class="dd-item" data-id="2">
                    <div class="dd-handle">Item 2</div>
                    <ol class="dd-list">
                        <li class="dd-item" data-id="3">
                            <div class="dd-handle">Item 3 </div>
                        </li>
                        <li class="dd-item" data-id="4">
                            <div class="dd-handle">Item 4</div>
                        </li>
                        <li class="dd-item" data-id="5">
                            <div class="dd-handle">Item 5</div>
                            <ol class="dd-list">
                                <li class="dd-item" data-id="6">
                                    <div class="dd-handle">Item 6</div>
                                </li>
                                <li class="dd-item" data-id="7">
                                    <div class="dd-handle">Item 7</div>
                                </li>
                                <li class="dd-item" data-id="8">
                                    <div class="dd-handle">Item 8</div>
                                </li>
                            </ol>
                        </li>
                        <li class="dd-item" data-id="9">
                            <div class="dd-handle">Item 9</div>
                        </li>
                        <li class="dd-item" data-id="10">
                            <div class="dd-handle">Item 10</div>
                        </li>
                    </ol>
                </li>
                <li class="dd-item" data-id="11">
                    <div class="dd-handle">Item 11</div>
                </li>
                <li class="dd-item" data-id="12">
                    <div class="dd-handle">Item 12</div>
                </li>
            </ol>
            --}}
        </div>

    </div>
    <div class="col-md-4">

        {{ Former::text('menuTitle','Name')->id('#menuTitle') }}
        {{ Former::text('slug','Permalink')->id('permalink') }}
        {{ Former::text('blockName','blockName') }}
        {{ Former::text('domain','Site Domain') }}

    </div>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        {{ Form::submit('Save',array('class'=>'btn btn-primary'))}}&nbsp;&nbsp;
        {{ HTML::link($back,'Cancel',array('class'=>'btn'))}}
    </div>
</div>
{{Former::close()}}

{{-- HTML::script('js/jquery.ui-contextmenu.js') --}}

{{-- HTML::style('js/fancytree/skin-bootstrap/ui.fancytree.css') --}}
{{-- HTML::script('js/fancytree/jquery.fancytree-all.js') --}}

{{-- HTML::script('js/fancytree/src/jquery.fancytree.dnd.js') --}}
{{-- HTML::script('js/fancytree/src/jquery.fancytree.edit.js') --}}
{{-- HTML::script('js/fancytree/src/jquery.fancytree.gridnav.js') --}}
{{-- HTML::script('js/fancytree/src/jquery.fancytree.table.js') --}}
{{-- HTML::script('js/fancytree/src/jquery.fancytree.glyph.js') --}}

{{ HTML::style('css/jquery.nestable.css') }}

{{ HTML::script('js/jquery.nestable.js') }}

<style type="text/css">
input.mparam{
    width:25px;
}

#mainTree select{
    width:100px;
}

.fa fa-* {
    width: 1.2em;
    height: 1.2em;
}
</style>


<script type="text/javascript">

$(document).ready(function() {

    $('#nestable_list').nestable();

    $('#menuTitle').on('keyup',function(){
        var title = $('#menuTitle').val();
        var slug = string_to_slug(title);
        $('#permalink').val(slug);
    });

});

</script>

<?php

    function buildMenu($menu_array,$is_sub = false)
    {
        global $id_cnt;
        /*
         * If the supplied array is part of a sub-menu, add the
         * sub-menu class instead of the menu ID for CSS styling
         */
        $attr = ($is_sub) ? ' class="dd-item"':' class="dd-list"';
        $attr = ' class="dd-list"';
        $menu = '<ol '.$attr.' >'."\n"; // Open the menu container

        /*
         * Loop through the array to extract element values
         */
        foreach($menu_array as $id => $properties) {

            /*
             * Because each page element is another array, we
             * need to loop again. This time, we save individual
             * array elements as variables, using the array key
             * as the variable name.
             */
            foreach($properties as $key => $val) {

                /*
                 * If the array element contains another array,
                 * call the buildMenu() function recursively to
                 * build the sub-menu and store it in $sub
                 */
                if(is_array($val))
                {
                    $sub = buildMenu($val, TRUE);
                }

                /*
                 * Otherwise, set $sub to NULL and store the
                 * element's value in a variable
                 */
                else
                {
                    $sub = NULL;
                    $$key = $val;
                }
            }

            /*
             * If no array element had the key 'url', set the
             * $url variable equal to the containing element's ID
             */
            if(!isset($url)) {
                $url = $id;
            }

            /*
             * Use the created variables to output HTML
             */
            $id_cnt++;
            $menu .= '<li class="dd-item" data-url="'.$url.'" data-id="'.$id_cnt.'" ><div class="dd-handle">'.$display.'</div>'.$sub.'</li>'."\n";

            /*
             * Destroy the variables to ensure they're reset
             * on each iteration
             */
            unset($url, $display, $sub);

        }

        /*
         * Close the menu container and return the markup for output
         */
        return $menu . '</ul>'."\n";
    }


?>

@stop