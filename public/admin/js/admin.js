/**
 * @author: zyh
 * @see: <a href="mailto:jikeytang@gmail.com">zyh</a>
 * @time: 2013-10-4 下午11:12
 * @info:
 */
$(function(){
    var gform = null;
    if(typeof (gform = document.myform) != 'undefined'){
        var modelName = gform.getAttribute('modelName');
        if(!modelName){
            console.log('modelName 为空，请在form中添加modelName属性！');
        }
    }

    // 批量删除
    delAll('#batchDel', 'myform', '?m=' + modelName + '&a=act&action=del', 'id[]');

    // 更新排序
    updateSort('#updateOrd', 'myform', '?m=' + modelName + '&a=act&action=ord', 'ord[]');

//    bakChoose('#bakDataTab', 'myform', 'id[]'); // 备份时非空检测

    $('.allChoose').allChoose({ name : 'id[]' });

    worksRequire(); // 设计作品验证
    linkRequire(); // 友情链接验证

    checkNoNull($('.btn'), $('.keywords')); // 搜索非空检查

    initBakData('#bakChoose', 'id[]');

    // changeVerify();
    pwdRequire();

//    setSelect();
});

/**
 * 批量删除
 * @param el 链接元素
 * @param form 需要改变的form
 * @param act 改变的action
 * @param name input name
 */
function delAll(el, form, act, name){
    var myForm = null;

    if(form){ myForm = document[form]; }

    $(el).on('click', function(){
        if(checkChoose(name)){
            art.confirm('确定要删除记录吗？', function(){
                myForm.action = act;
                alert(myForm.action);
                myForm.submit();
            }, function(){
                return false;
            });
        } else {
            art.alert('请先选择删除记录!');
        }

    });
}

/**
 * 更新排序
 * @param el 链接元素
 * @param form 需要改变的form
 * @param act 改变的action
 * @param name input name
 */
function updateSort(el, form, act, name){
    var myForm = null;

    if(form){ myForm = document[form]; }

    $(el).on('click', function(){
        myForm.action = act;
        myForm.submit();
    });
}

/**
 * 判断是否有元素选中
 * @param names input name值
 * @returns {boolean}
 */
function checkChoose(names){
    var boxs = $('input[type="checkbox"][name="' + names + '"]');
    if(boxs.is(':checked')){
        return true;
    } else {
        return false;
    }
}

// 非空验证
function checkNoNull(btn, ele){
    btn.on('click', function(){
        if(ele.length < 1){ return; }
        if(ele.val().length < 1){
            art.alert('请输入关键字!');
            return false;
        }
    });
}

/**
 * 备份非空检查
 * @param el
 * @param form
 * @param name
 */
function bakChoose(el, form, name){
    var myForm = null;

    if(form){ myForm = document[form]; }

    $(el).on('click', function(){
        if(checkChoose(name)){
            myForm.submit();
        } else {
            art.alert('请先选择备份数据表!');
            return false;
        }

    });
}

// 初始化全部选中状态
function initBakData(el, names){
    if($(el).length < 1){
        return ;
    } else {
        $(el).one('click.check', function(){
            $('input[type="checkbox"][name="' + names + '"]').attr('checked', true);
        }).trigger('click.check');
    }
}

// 作品新增验证
function worksRequire(){
    $.formValidator.initConfig({autotip : true, formid : 'myform', errorfocus : true, onerror : function(msg){ }});
    $('#catid').formValidator({onshow : '请选择栏目', onfocus : '请选择栏目'}).inputValidator({min : 1, max : 50, onerror : '请选择栏目'});
//    $('#title').formValidator({onshow : '标题不能为空', onfocus : '标题不能为空'}).inputValidator({min : 1, max : 50, onerror : '标题不能为空'});
}

// 友情链接验证
function linkRequire(){
    $.formValidator.initConfig({autotip : true, formid : "myform", onerror : function(msg){}});
    $("#url").formValidator({onshow : "请填写url", onfocus : "请填写url"}).inputValidator({min : 1, max : 50, onerror : "url不能为空"});
    $("#name").formValidator({onshow : "站名不能为空", onfocus : "站名不能为空"}).inputValidator({min : 1, max : 50, onerror : "站名不能为空"});
}

// 编辑分类是设定选择项
function setSelect(){
    var sortVal = $('#sortNameId').val();
    if(sortVal){
        chooseSelect(sortVal);
    }
}

function chooseSelect(obj){
    $('#colId option[value="' + obj + '"]').attr('selected', true);
}

function changeVerify(){
    $('#verifyImg').on('click', function(){
        this.src = PUBLIC + '/Verify/random' + Math.random();
    });
}

function pwdRequire(){
    $.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
    $("#oldpwd").formValidator({onshow:"旧密码不能为空",onfocus:"旧密码不能为空"}).inputValidator({min:1,max:50,onerror:"旧密码不能为空"});
    $("#newpwd").formValidator({onshow:"新密码不能为空",onfocus:"新密码不能为空"}).inputValidator({min:1,max:50,onerror:"新密码不能为空"});
    $("#renewpwd").formValidator({onshow:"确认新密码",onfocus:"确认新密码"}).inputValidator({min:1,max:50,onerror:"确认新密码"});
}

