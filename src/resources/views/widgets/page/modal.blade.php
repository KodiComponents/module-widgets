<div class="modal" tabindex="-1" role="dialog" id="widgetsPopupList">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('dashboard::core.buttons.popup_close')">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h5>@lang('widgets::core.title.list')</h5>
            </div>
            <div class="panel no-margin" v-if="hasWidgets()">
                <div v-for="(group_title, group) in widgets">
                    <div v-if="groupHasWidgets(widgets)">
                        <div class="panel-heading">
                            <span class="panel-title">@{{ group_title }}</span>
                        </div>
                        <div class="panel-body padding-sm">
                            <button type="button" data-icon="puzzle-piece" class="btn btn-default btn-labeled" v-for="widget in group" v-on:click="place(widget)">
                                @{{ widget.name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="alert alert-info alert-dark no-margin text-center" v-if="!hasWidgets()">
                @lang('widgets::core.messages.all_widgets_placed')
            </h3>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    @lang('widgets::core.button.popup_close')
                </button>
            </div>
        </div>
    </div>
</div>