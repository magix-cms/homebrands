{extends file="layout.tpl"}
{block name='head:title'}homebrands{/block}
{block name='body:id'}homebrands{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_slide#|ucfirst}: {$slides|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=slide&amp;action=add" title="{#add_slide#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_slide#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">Homebrands</h1>
{/block}
{block name='article:content'}
{if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
    <section class="panel col-xs-12 col-md-12">
    {if $debug}
        {$debug}
    {/if}
    <header class="panel-header">
        <h2 class="panel-heading h5">{#root_homebrands#}</h2>
    </header>
    <div class="panel-body panel-body-form">
        <div class="mc-message-container clearfix">
            <div class="mc-message"></div>
        </div>
        {include file="section/form/table-form-2.tpl" data=$slides idcolumn='id_slide' activation=false search=false sortable=true controller="homebrands"}
    </div>
    </section>
    </div>
    {include file="modal/delete.tpl" data_type='slide' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#modal_delete_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/homebrands/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof homebrands == "undefined")
            {
                console.log("homebrands is not defined");
            }else{
                homebrands.run();
            }
        });
    </script>
{/block}
