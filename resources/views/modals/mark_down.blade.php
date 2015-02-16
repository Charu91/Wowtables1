<div style="z-index: 999999" class="modal fade modal-fullscreen force-fullscreen" id="markdownmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button style="color: #ffffff;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Markdown Editor</h4>
            </div>
            <div style="margin-top:57px;" class="modal-body">
                <div id="editor">
                    <textarea v-model="input"></textarea>
                    <div  style="overflow:-moz-scrollbars-vertical;overflow-y:auto;" class="container">
                        <div style="width: 100%;" v-html="input | marked"></div>
                    </div>
                    <h5 style="margin: 10px;"  class="text-right text-muted" >*This is how it will look.</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Insert Text</button>
                <h5 class="float-left">For Refercence on How to use Markdown,
                    <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" >
                        Click Here
                    </a>
                </h5>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->