/**
 * Created by Lisa on 2016/6/5.
 */
var key_value_list =  {
        "A":"姓名",
        "B":"年龄",
        "C":"省份",
        "D":"城市"
};
var schoolOp = new schoolList();
function delSchool(school)
{
    var len = school.length;
    for(i=0; i<len; i++)
    {
        school.remove(0);

    }
}

function addSchool(index, school)
{
    //alert(index);
    for(j=0; j<schoolOp[index].length; j++)
    {
        school.options.add(schoolOp[index][j]);
    }
}


var schedule = {
    init:function () {
        console.log(this.getQueryString("lost_column"));
        console.log(this.getQueryString("id"));

        this.generate_form(this.getQueryString("lost_column"),this.getQueryString("token"),this.getQueryString("id"));
    },
    generate_form:function(lost_data,token,id){
        var list = lost_data.split(",");
        var tpl = "";
        for(var i = 0;i<list.length;i++){
            if(list[i]){
                tpl+=this.form_item_tpl(list[i]);
            }

        }

        tpl+="<input type='hidden' name='token' value='"+token+"'>";
        tpl+="<input type='hidden' name='id' value='"+id+"'>"
        $("#J-generate_wrapper").html(tpl);
    },
    hasLocation:false,
    form_item_tpl:function(key){
        //约定name为 col-A
        var tpl = "";
        if(key == "C" || key == "D"){
            if(!this.hasLocation){
                tpl=[
                    '<div id="province_manager" class="form-group">',
                    '<label class="col-sm-2 control-label">地区</label>',
                    '<div class="col-sm-2">',
                    '<select class="prov form-control" name="col-C"></select>',//需要改
                    '</div>',
                    '<div class="col-sm-2">',
                    '<select class="city form-control" disabled="disabled" name="col-D"></select>',//需要改
                    '</div>',
                    '</div>'
                ].join("");
                this.hasLocation = true;
            }
        }else{
            tpl = [
                '<div class="form-group">',
                '<label class="col-sm-2 control-label">',key_value_list[key],'</label>',
                '<div class="col-sm-8 col-md-4">',
                '<input type="text" class="form-control"  check-type="required" placeholder="请输入',key_value_list[key],'" name="col-',key,'" required>',
                '</div>',
                '</div>'
            ].join("");
        }

        return tpl;
    },
    getQueryString:function (key) {
        var sURL = window.document.URL;
        if(sURL.indexOf("?")>0){
            var arr = sURL.split("?")[1];
            var key_values = arr.split("&");
            for(var i = 0;i<key_values.length;i++){
                var item = key_values[i].split("=");
                if(item[0] == key){
                    return item[1];
                }
            }
        }
        return null;
    },
    addSchool:function () {
        school = new schoolList();
        $("#province_manager .prov").change(function(){
            var index = $("#province_manager .prov").find("option:selected").index();
            var schoolObj = document.getElementById("J-school");
            delSchool(schoolObj);
            addSchool(index,schoolObj);

        });
    }
}

$(function(){
    schedule.init();

    $("form").validation();
    $("button[type='submit']").on('click',function(event){
        if ($("form").valid(this,"error!")==false){
            return false;
        }
    });

    //
    $("#province_manager").citySelect({
        nodata:"none",
        required:false
    });



})