/**
 * @author: Jikey
 * @see: <a href="mailto:jikeytang@gmail.com">Jikey</a>
 * @time: 2013-10-7 下午9:34
 * @info:
 */
Modernizr.load([
    {
        load : [PUBLIC + '/admin/js/libs/jquery-1.8.3.min.js', PUBLIC + '/admin/js/artDialog-5.0.4/artDialog.min.js', PUBLIC + '/admin/js//artDialog-5.0.4/artDialog.plugins.min.js', PUBLIC + '/admin/js/common.js'],
        complete : function(){
            
        }
    },
    {
        load : [PUBLIC + '/admin/js/libs/formvalidator.js', PUBLIC + '/admin/js/admin.js'],
        complete : function(){

        }
    }
]);
