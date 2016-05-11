// checking if IE: this variable will be understood by IE: isIE = !false
isIE = /*@cc_on!@*/false;

Control.Slider.prototype.setDisabled = function()
{
    this.disabled = true;

    if (!isIE)
    {
        this.track.parentNode.className = this.track.parentNode.className + ' disabled';
    }
};

function camel_layered_hide_products()
{
    var items = $('camel_filters_list').select('a', 'input');
    n = items.length;
    for (i = 0; i < n; ++i) {
        items[i].addClassName('camel_layered_disabled');
    }

    if (typeof (camel_slider) != 'undefined')
        camel_slider.setDisabled();

    var divs = $$('div.camel_loading_filters');
    for (var i = 0; i < divs.length; ++i)
        divs[i].show();
}

function camel_layered_show_products(transport)
{
    var resp = {};
    if (transport && transport.responseText) {
        try {
            resp = eval('(' + transport.responseText + ')');
        }
        catch (e) {
            resp = {};
        }
    }

    if (resp.products) {

        var ajaxUrl = $('camel_layered_ajax').value;

        if ($('camel_layered_container') == undefined) {

            var c = $$('.col-main')[0];// alert(c.hasChildNodes());
            if (c.hasChildNodes()) {
                while (c.childNodes.length > 2) {
                    c.removeChild(c.lastChild);
                }
            }

            var div = document.createElement('div');
            div.setAttribute('id', 'camel_layered_container');
            $$('.col-main')[0].appendChild(div);

        }

        var el = $('camel_layered_container');
        el.update(resp.products.gsub(ajaxUrl, $('camel_layered_url').value));
        catalog_toolbar_init();

        $('catalog-filters').update(
                resp.layer.gsub(
                        ajaxUrl,
                        $('camel_layered_url').value
                        )
                );

        $('camel_layered_ajax').value = ajaxUrl;
       
       jQuery(document).ready(function() {
            jQuery(".lazy-load").unveil(200, function() {
                jQuery(this).load(function() {
                    this.style.opacity = 1;
                });
            });
        });

    }

    var items = $('camel_filters_list').select('a', 'input');
    n = items.length;
    for (i = 0; i < n; ++i) {
        items[i].removeClassName('camel_layered_disabled');
    }
    if (typeof (camel_slider) != 'undefined')
        camel_slider.setEnabled();
}

function camel_layered_add_params(k, v, isSingleVal)
{
    var el = $('camel_layered_params');
    var params = el.value.parseQuery();

    var strVal = params[k];
    if (typeof strVal == 'undefined' || !strVal.length) {
        params[k] = v;
    }
    else if ('clear' == v) {
        params[k] = 'clear';
    }
    else {
        if (k == 'price')
            var values = strVal.split(',');
        else
            var values = strVal.split('-');

        if (-1 == values.indexOf(v)) {
            if (isSingleVal)
                values = [v];
            else
                values.push(v);
        }
        else {
            values = values.without(v);
        }

        params[k] = values.join('-');
    }

    el.value = Object.toQueryString(params).gsub('%2B', '+');
}



function camel_layered_make_request()
{
    camel_layered_hide_products();

    var params = $('camel_layered_params').value.parseQuery();

    if (!params['dir'])
    {
        $('camel_layered_params').value += '&dir=' + 'desc';
    }

    new Ajax.Request(
            $('camel_layered_ajax').value + '?' + $('camel_layered_params').value,
            {
                method: 'get',
                onSuccess: camel_layered_show_products
            }
    );
    
}


function camel_layered_update_links(evt, className, isSingleVal)
{
    var link = Event.findElement(evt, 'A'),
            sel = className + '-selected';

    if (link.hasClassName(sel))
        link.removeClassName(sel);
    else
        link.addClassName(sel);

    //only one  price-range can be selected
    if (isSingleVal) {
        var items = $('camel_filters_list').getElementsByClassName(className);
        var i, n = items.length;
        for (i = 0; i < n; ++i) {
            if (items[i].hasClassName(sel) && items[i].id != link.id)
                items[i].removeClassName(sel);
        }
    }

    camel_layered_add_params(link.id.split('-')[0], link.id.split('-')[1], isSingleVal);

    camel_layered_make_request();

    Event.stop(evt);
}


function camel_layered_attribute_listener(evt)
{
    camel_layered_add_params('p', 1, 1);
    camel_layered_update_links(evt, 'camel_layered_attribute', 0);
}


function camel_layered_price_listener(evt)
{
    camel_layered_add_params('p', 1, 1);
    camel_layered_update_links(evt, 'camel_layered_price', 1);
}

function camel_layered_clear_listener(evt)
{
    var link = Event.findElement(evt, 'A'),
            varName = link.id.split('-')[0];

    camel_layered_add_params('p', 1, 1);
    camel_layered_add_params(varName, 'clear', 1);

    if ('price' == varName) {
        var from = $('adj-nav-price-from'),
                to = $('adj-nav-price-to');

        if (Object.isElement(from)) {
            from.value = from.name;
            to.value = to.name;
        }
    }

    camel_layered_make_request();

    Event.stop(evt);
}


function roundPrice(num) {
    num = parseFloat(num);
    if (isNaN(num))
        num = 0;

    return Math.round(num);
}

function camel_layered_category_listener(evt) {
    var link = Event.findElement(evt, 'A');
    var catId = link.id.split('-')[1];

    var reg = /cat-/;
    if (reg.test(link.id)) { //is search
        camel_layered_add_params('cat', catId, 1);
        camel_layered_add_params('p', 1, 1); 
        camel_layered_make_request();
        Event.stop(evt);
    }
    //do not stop event
}

function catalog_toolbar_listener(evt) {
    catalog_toolbar_make_request(Event.findElement(evt, 'A').href);
    Event.stop(evt);
}

function catalog_toolbar_make_request(href)
{
    var pos = href.indexOf('?');
    if (pos > -1) {
        $('camel_layered_params').value = href.substring(pos + 1, href.length);
    }
    camel_layered_make_request();
}


function catalog_toolbar_init()
{
    var items = $('camel_layered_container').select('.pages a', '.pages li a', '.view-mode a', '.sort-by a');
    var i, n = items.length;
    for (i = 0; i < n; ++i) {
        Event.observe(items[i], 'click', catalog_toolbar_listener);
    }
}

function camel_layered_dt_listener(evt) {
    var e = Event.findElement(evt, 'DT');
    e.nextSiblings()[0].toggle();
    e.toggleClassName('camel_layered_dt_selected');
}

function camel_layered_clearall_listener(evt)
{
    var params = $('camel_layered_params').value.parseQuery();
    $('camel_layered_params').value = 'clearall=true';
    if (params['q'])
    {
        $('camel_layered_params').value += '&q=' + params['q'];
    }
    camel_layered_make_request();
    Event.stop(evt);
}

function price_input_listener(evt) {
    if (evt.type == 'keypress' && 13 != evt.keyCode)
        return;

    if (evt.type == 'keypress') {
        var inpObj = Event.findElement(evt, 'INPUT');
    } else {
        var inpObj = Event.findElement(evt, 'BUTTON');
    }

    var sKey = inpObj.id.split('---')[1];
    var numFrom = roundPrice($('price_range_from---' + sKey).value),
            numTo = roundPrice($('price_range_to---' + sKey).value);

    if ((numFrom < 0.01 && numTo < 0.01) || numFrom < 0 || numTo < 0)
        return;

    camel_layered_add_params('p', 1, 1);
    camel_layered_add_params(sKey, numFrom + ',' + numTo, true);
    camel_layered_make_request();
}

function camel_layered_init()
{
    var items, i, j, n,
            classes = ['category', 'attribute', 'icon', 'price', 'clear', 'dt', 'clearall'];

    for (j = 0; j < classes.length; ++j) {
        items = $('camel_filters_list').select('.camel_layered_' + classes[j]);
        n = items.length;
        for (i = 0; i < n; ++i) {
            Event.observe(items[i], 'click', eval('camel_layered_' + classes[j] + '_listener'));
        }
    }

    items = $('camel_filters_list').select('.price-input');
    n = items.length;
    var btn = $('price_button_go');
    for (i = 0; i < n; ++i)
    {
        btn = $('price_button_go---' + items[i].value);
        if (Object.isElement(btn)) {
            Event.observe(btn, 'click', price_input_listener);
            Event.observe($('price_range_from---' + items[i].value), 'keypress', price_input_listener);
            Event.observe($('price_range_to---' + items[i].value), 'keypress', price_input_listener);
        }
    }
// finish new fix code    
}

function create_price_slider(width, from, to, min_price, max_price, sKey)
{
    var price_slider = $('camel_layered_price_slider' + sKey);

    return new Control.Slider(price_slider.select('.handle'), price_slider, {
        range: $R(0, width),
        sliderValue: [from, to],
        restricted: true,
        onChange: function(values) {
            var f = calculateSliderPrice(width, from, to, min_price, max_price, values[0]),
                    t = calculateSliderPrice(width, from, to, min_price, max_price, values[1]);

            camel_layered_add_params(sKey, f + ',' + t, true);

            $('price_range_from' + sKey).update(f);
            $('price_range_to' + sKey).update(t);

            camel_layered_make_request();
        },
        onSlide: function(values) {
            $('price_range_from' + sKey).update(calculateSliderPrice(width, from, to, min_price, max_price, values[0]));
            $('price_range_to' + sKey).update(calculateSliderPrice(width, from, to, min_price, max_price, values[1]));
        }
    });
}

function calculateSliderPrice(width, from, to, min_price, max_price, value)
{
    var calculated = roundPrice(((max_price - min_price) * value / width) + min_price);

    return calculated;
}
