<div class="btn-group">
  <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="mdi-navigation-more-vert"></i>
  </button>
  <span class="dropdown-arrow dropdown-arrow-inverse"></span>
  <ul class="dropdown-menu dropdown-inverse" role="menu">
    @foreach($actions as $action)
      <li>{{ $action }}</a>
      </li>
    @endforeach
  </ul>
</div>