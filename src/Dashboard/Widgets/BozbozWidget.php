<?php

namespace Bozboz\Admin\Dashboard\Widgets;

class BozbozWidget implements Widget
{
    public function render()
    {
        $widgetApiUrl = config('admin.widget_api_url');
        if (!$widgetApiUrl) {
            return '';
        }
        return <<<HTML
<div id="bozboz-widgets"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.2/iframeResizer.min.js" integrity="sha512-dnvR4Aebv5bAtJxDunq3eE8puKAJrY9GBJYl9GC6lTOEC76s1dbDfJFcL9GyzpaDW4vlI/UjR8sKbc1j6Ynx6w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $.get('{$widgetApiUrl}', function(data) {
        const style = document.createElement('style');
        style.innerHTML = `
            #bozboz-widgets {
                display: none;
            }
            .boz-widget iframe {
                width: 100%;
                height: 100%;
                border: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            .boz-widget {
                display: flex;
                border-radius: 1rem;
                overflow: hidden;
                padding: 0;
            }
            .boz-widget > * {
                width: 100%;
                height: 100%;
            }
        `;
        document.head.appendChild(style);
        $.each(data, function(index, value) {
            const container = document.createElement('div');
            container.classList.add('col-md-6', 'col-lg-4');
            container.style.maxWidth = '40rem';
            const widget = document.createElement('div');
            widget.classList.add('boz-widget', 'panel', 'panel-default');
            const iframe = document.createElement('iframe');
            widget.appendChild(iframe);
            container.appendChild(widget);
            iframe.src = value.iframeUrl;
            iframe.onload = function(e) {
                iFrameResize({}, e.target);
            };
            document.querySelector('#bozboz-widgets').parentNode.appendChild(container);
        });
    });
</script>
HTML;
    }
}
