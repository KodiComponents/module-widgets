@push('page-content')
<div class="panel-heading panel-toggler" data-hotkeys="shift+w">
	<span class="panel-title" data-icon="cubes">@lang('widgets::core.title.list')</span>
</div>
<div class="panel-spoiler" id="page-widgets">
	<div class="panel-body">
		@if (is_null($page->id))
		<h4>@lang('widgets::core.title.copy_widgets')</h4>
		<select name="widgets[from_page_id]" class="col-md-12">
			<option value="">@lang('widgets::core.label.dont_copy_widgets')</option>
			@foreach ($pages as $p)
			<option value="<{{ $p['id'] }}" {{ $p['id'] == $page->parent_id ? ' selected="selected"': '' }} > {{ str_repeat('- ', $p['level'] * 2) }}{{ $p['title'] }}</option>
			@endforeach
		</select>
		@else

		@if (acl_check('widget_settings::location'))

		<button type="button" class="btn btn-success btn-sm" v-on:click="openPopup" data-icon="plus">
			@lang('widgets::core.button.add_to_page')
		</button>

		@if (acl_check('layout::rebuild'))
			{!! Form::button(trans('pages::layout.button.rebuild'), [
				'data-icon' => 'refresh',
				'class' => 'btn btn-inverse btn-sm',
				'data-api-url' => '/api.layout.rebuild'
			]) !!}
		@endif
		@endif
	</div>


	<table class="table table-hover" id="widget-list">
		<colgroup>
			<col />
			<col width="100px" />
			<col width="280px" />
		</colgroup>
		<tbody>
		@foreach ($widgetsCollection as $widget)
		@include('widgets::widgets.page.row', ['widget' => $widget->getObject(), 'block' => $widget->getBlock(), 'position' => $widget->getPosition(), 'page' => $page])
		@endforeach
		</tbody>
	</table>
	@endif

	@include('widgets::widgets.page.modal')
	<hr class="panel-wide" />
</div>
@endpush

@push('scripts')
<script src="/backend/cms/js/WidgetController.js"></script>
@endpush