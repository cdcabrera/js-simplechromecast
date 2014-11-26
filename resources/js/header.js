/**
 * no-js
 */
(function(el)
{
    if(el)
    {
        var args = Array.prototype.slice.call(arguments, 1);

        while(args.length)
        {
            el.className = el.className.replace(args.shift(),'');
        }
    }


})(document.getElementsByTagName('HTML')[0], 'no-js');