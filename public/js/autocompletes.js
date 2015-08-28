$(document).ready(function() {

    $('.tag_related').tagsInput({
        'autocomplete_url': base + 'ajax/product',
        'height':'100px',
        'width':'100%',
        'interactive':true,
        'onChange' : function(c){
            console.log(c);

        },
        'onAddTag' : function(t){
            console.log(t);

        },
        'onRemoveTag' : function(t){
            console.log(t);
        },
        'defaultText':'add SKU',
        'removeWithBackspace' : true,
        'minChars' : 0,
        'maxChars' : 0, //if not provided there is no limit,
        'placeholderColor' : '#666666'
    });


    $('#relatedProducts_tag')
        .data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
            var inner_html = '<a><div class="list_item_container"><div class="image" style="display:inline-block;">' + item.pic + '</div><div class="item-info">' + item.value + '<div class="item-description">' + item.description + '</div></div></div></a>';
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append(inner_html)
                .appendTo( ul );
        };

    $('.tag_recommended').tagsInput({
        'autocomplete_url': base + 'ajax/product',
        'height':'100px',
        'width':'100%',
        'interactive':true,
        'onChange' : function(c){

        },
        'onAddTag' : function(t){
            console.log(t);
        },
        'onRemoveTag' : function(t){
            console.log(t);
        },
        'defaultText':'add SKU',
        'removeWithBackspace' : true,
        'minChars' : 0,
        'maxChars' : 0, //if not provided there is no limit,
        'placeholderColor' : '#666666'
    });


    $('#recommendedProducts_tag')
        .data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
            var inner_html = '<a><div class="list_item_container"><div class="image" style="display:inline-block;">' + item.pic + '</div><div class="item-info">' + item.value + '<div class="item-description">' + item.description + '</div></div></div></a>';
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append(inner_html)
                .appendTo( ul );
        };

    /*
    $('.autocomplete_product').autocomplete({
        source: base + 'ajax/product', // name of controller followed by function
        select: function( event, ui){
            var inner_html = '<li><div class="list_item_container"><div class="image" style="display:inline-block;">' + ui.item.pic + '</div><div style="display:inline-block; padding-left:8px;" >' + ui.item.value + '<p class="description">' + ui.item.description + '</p><input type="hidden" name="relatedProducts[]" value="'+ ui.item.id +'" ></div></li>';
            $('#relatedList').prepend(inner_html);
            $('#relatedProducts').val('');
        }
    }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
        var inner_html = '<a><div class="list_item_container"><div class="image" style="display:inline-block;">' + item.pic + '</div><div >' + item.value + '</div><div class="description">' + item.description + '</div></div></a>';
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append(inner_html)
            .appendTo( ul );
    };

    $('.autocomplete_recommended').autocomplete({
        source: base + 'ajax/product', // name of controller followed by function
        select: function( event, ui){
            var inner_html = '<li><div class="list_item_container"><div class="image" style="display:inline-block;">' + ui.item.pic + '</div><div style="display:inline-block; padding-left:8px;" >' + ui.item.value + '<p class="description">' + ui.item.description + '</p><input type="hidden" name="recommendedProducts[]" value="'+ ui.item.id +'" ></div></li>';
            $('#recommendedList').prepend(inner_html);
            $('#recommendedProducts').val('');
        }
    }).data( 'ui-autocomplete' )._renderItem = function( ul, item ) {
        var inner_html = '<a><div class="list_item_container"><div class="image" style="display:inline-block;">' + item.pic + '</div><div >' + item.value + '</div><div class="description">' + item.description + '</div></div></a>';
        return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append(inner_html)
            .appendTo( ul );
    };
    */

});
