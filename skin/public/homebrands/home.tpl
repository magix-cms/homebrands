{widget_homebrands_data}
{if is_array($brands) && $brands != null}
    {if $amp}
        {$ref = $brands|end}
        <amp-carousel id="{$id_slider}" class="carousel2"
                      type="slides"
                      autoplay
                      delay="3000"
                      layout="responsive"
                      height="{$ref.img['small']['h']}"
                      width="{$ref.img['small']['w']}"
                      type="slides">
            {foreach $brands as $brand}
                <div class="slide">
                    <amp-img src="{$brand.img['small']['src']}"
                             alt="{$brand.title_slide}"
                             title="{$item.name}"
                             layout="fill" itemprop="image"></amp-img>

                    <div class="caption">
                        <div class="text">
                            <h3 class="h2">{$brand.title_slide}</h3>
                            {if !empty($brand.desc_slide)}
                                <p class="lead">{$brand.desc_slide}</p>
                            {/if}
                        </div>
                    </div>
                </div>
            {/foreach}
        </amp-carousel>
    {else}
        <div id="homebrands">
            <div class="container">
                <div class="owl-carousel owl-theme owl-brands">
                    {foreach $brands as $brand}
                        <div class="brand">
                            {strip}
                                {*<picture>
                                <!--[if IE 9]><video style="display: none;"><![endif]-->
                                <source sizes="100%"
                                        media="(min-width: {$brand.img['medium']['w']}px)"
                                        srcset="{$brand.img['large']['src']} {$brand.img['large']['w']}w">
                                <source sizes="100%"
                                        media="(min-width: {$brand.img['small']['w']}px)"
                                        srcset="{$brand.img['medium']['src']} {$brand.img['medium']['w']}w">
                                <source sizes="100%"
                                        srcset="{$brand.img['small']['src']} {$brand.img['small']['w']}w">
                                <!--[if IE 9]></video><![endif]-->
                                <img src="{$brand.img['small']['src']}"
                                     sizes="100%"
                                     srcset="{$brand.img['large']['src']} {$brand.img['large']['w']}w,
                                {$brand.img['medium']['src']} {$brand.img['medium']['w']}w,
                                {$brand.img['small']['src']} {$brand.img['small']['w']}w"
                                     alt="{$brand.title_slide}" title="{$brand.title_slide}" />
                                </picture>*}
                            <img data-src="{$brand.img['medium']['src']}"
                                 width="{$brand.img['medium']['w']}"
                                 height="{$brand.img['medium']['h']}"
                                 {*alt="{$brand.title_slide}" title="{$brand.title_slide}"*}
                                 class="img-responsive owl-lazy" />{/strip}
                            {*<div class="carousel-caption">
                                <div>
                                    <h4 class="h3">{$brand.title_slide}</h4>
                                    {if !empty($brand.desc_slide)}
                                        <p>{$brand.desc_slide}</p>
                                    {/if}
                                </div>
                            </div>*}
                            {if isset($brand.url_slide) && !empty($brand.url_slide)}
                                <a href="{$brand.url_slide}" title="{$key.title_slide}" class="all-hover{if $brand.blank_slide} targetblank{/if}">{$brand.title_slide}</a>
                            {/if}
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}
{/if}