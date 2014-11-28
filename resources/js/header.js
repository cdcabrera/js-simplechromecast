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


    var testo = 'funky        =' +
        '1' +
        '2' +
        '3' +
        '4
        5';


    function _Bob(){

        var local = testo;

    }

    _Bob();


    function _Bob2(){

        var local = testo;

    }

    _Bob2();


})(document.getElementsByTagName('HTML')[0], 'no-js');