{widget_homebrands_data}
{if is_array($brands) && $brands != null}
    {if $amp}
        {$ref = $brands|end}
        <amp-carousel id="{$id_brandr}" class="carousel2"
                      type="brands"
                      autoplay
                      delay="3000"
                      layout="responsive"
                      height="{$ref.img['small']['h']}"
                      width="{$ref.img['small']['w']}"
                      type="brands">
            {foreach $brands as $brand}
                <div class="brand">
                    <amp-img src="{$brand.img['small']['src']}"
                             alt="{$brand.title_brand}"
                             title="{$item.name}"
                             layout="fill" itemprop="image"></amp-img>

                    <div class="caption">
                        <div class="text">
                            <h3 class="h2">{$brand.title_brand}</h3>
                            {if !empty($brand.desc_brand)}
                                <p class="lead">{$brand.desc_brand}</p>
                            {/if}
                        </div>
                    </div>
                </div>
            {/foreach}
        </amp-carousel>
    {else}
        {*<pre>{$brands|print_r}</pre>*}
        <div id="homebrands">
            <div class="container">
                <div class="brands">
                {foreach $brands as $brand}
                    <div class="brand">
                        {include file="img/img.tpl" img=$brand.img lazy=true size='small'}
                        {if isset($brand.link_slide) && !empty($brand.link_slide.url)}
                            <a href="{$brand.link_slide.url}" title="{$brand.link_slide.title}" class="all-hover{if $brand.blank_slide} targetblank{/if}">{$brand.link_slide.title}</a>
                        {/if}
                    </div>
                {/foreach}
                </div>
            </div>
        </div>
    {/if}
{/if}