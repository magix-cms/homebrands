{extends file="layout.tpl"}
{block name="stylesheets"}
    {headlink rel="stylesheet" href="/{baseadmin}/min/?f=plugins/{$smarty.get.controller}/css/admin.min.css" media="screen"}
{/block}
{block name='head:title'}homebrands{/block}
{block name='body:id'}homebrands{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des slides">homebrands</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header {*panel-nav*}">
                    <h2 class="panel-heading h5">{if $edit}{#edit_slide#|ucfirst}{else}{#add_slide#|ucfirst}{/if}</h2>
                    {*<ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{#text#}</a></li>
                        {if $edit}<li role="presentation"><a href="#image" aria-controls="image" role="tab" data-toggle="tab">{#image#}</a></li>{/if}
                    </ul>*}
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="form/slide.tpl" controller="homebrands"}
                </div>
            </section>
        </div>
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=plugins/homebrands/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            if (typeof homebrands == "undefined")
            {
                console.log("homebrands is not defined");
            }else{
                homebrands.runEdit();
            }
        });
    </script>
{/block}