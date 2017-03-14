function getdata(){
    $('#submit').attr('disabled','true');
    $('#submit').addClass('disabled');
    $('#submit span').html('正在搜索，请稍候...');
    var page_size = $('#page_size').val();
    if(page_size.length <= 0){
        page_size = 20;
    }
    var q = $('#q').val();
    var cat = $('#cat').val();
    var itemloc = $('#itemloc').val();
    var sort = $('#sort').val();
    var sortby = $('#sortby').val();
    var is_tmall = false;
    if($("#is_tmall").is(":checked")){
        is_tmall = true;
    }
    var is_overseas = false;
    if($("#is_overseas").is(":checked")){
        is_overseas = true;
    }
    var start_price = $('#start_price').val();
    if(start_price.length > 0){
        start_price = Math.floor(start_price * 100) / 100;
    }
    var end_price = $('#end_price').val();
    if(end_price.length > 0){
        end_price = Math.floor(end_price * 100) / 100;
    }
    var start_tk_rate = $('#start_tk_rate').val();
    if(start_tk_rate.length > 0){
        start_tk_rate = Math.floor(start_tk_rate * 100) / 100;
    }
    var end_tk_rate = $('#end_tk_rate').val();
    if(end_tk_rate.length > 0){
        end_tk_rate = Math.floor(end_tk_rate * 100) / 100;
    }
    var platform = $('#platform').val();
    var url = '?act=search&q='+q+'&cat='+cat+'&itemloc='+itemloc+'&sort='+sort+'&sortby='+sortby+'&is_tmall='+is_tmall+'&is_overseas='+is_overseas+'&start_price='+start_price+'&end_price='+end_price+'&start_tk_rate='+start_tk_rate+'&end_tk_rate='+end_tk_rate+'&platform='+platform+'&page_no=1&page_size='+page_size;
    window.location.href = url;
    return true;
    return false;


    if(p == 1){
        tips('正在搜索，请稍候...');
        $('#result').hide();
        $('#result table tbody').empty();

    }
    
    var timestap = new Date();
    $.ajax({
        type: 'POST',
        url: '?act=search',
        data: {'timestap':timestap,'q':q,'p':p,'pagesize':pagesize},
        async:true,
        cache:false,
        dataType:"json",
        success: function(data){
            if(data.status == 0){
                var result = data.results.n_tbk_item;
                if(p == 1){
                    pages = Math.ceil(data.total_results/pagesize);
                }
                for (i=0;i<result.length;i++) {
                    var small_images = result[i].small_images.string;
                    var small_images_str = '';
                    var user_type = '集市';
                    if(result[i].user_type == 1){
                        user_type = '商城'
                    }
                    for(var j=0;j<small_images.length;j++){
                        small_images_str += '<img src="'+small_images[j]+'" />';
                    }
                    htmlTmp += '<tr><td>'+(i+1)+'</td><td>'+result[i].num_iid+'</td><td><a href="'+result[i].item_url+'" target="_blank">'+result[i].title+'</a></td><td><img src="'+result[i].pict_url+'" /></td><td class="imglist">'+small_images_str+'</td><td>'+result[i].reserve_price+'</td><td>'+result[i].zk_final_price+'</td><td>'+user_type+'</td><td>'+result[i].provcity+'</td><td>'+result[i].seller_id+'</td><td>'+result[i].nick+'</td><td>'+result[i].volume+'</td></tr>';
                }
            }else{
                if(data.status == 2){
                    tips('参数无效，请检查');
                }else{
                    tips(data.message+'，请检查');
                }
            }
            //if(p >= pages -1 ){
                if(pages > 0){
                    tips('');
                    $('#result table tbody').append(htmlTmp);
                    $('#count').html('搜索结果：共'+data.total_results+'条记录。');
                    $('#result').show();
                }
                p = 0;
                htmlTmp = '';
            //}else{
                //p += 1;
                //getdata();
            //}
        },
        error: function(data){
            tips('');
            p = 1;
            pages = 0;
            htmlTmp = '';
            tips('网络连接失败，请重试');
            return false;
        }
    });
}

function getHtml(url,type){
    var regexp=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
    if(!regexp.test(url)){
        tips('');
        tips('网址有误，请检查'+url);
        return '';
    }
    var htmlcontent = '';
    var timestap = new Date();
    $.ajax({
        type: 'POST',
        url: '?act=gethtml',
        data: {'timestap':timestap,'url':url,'type':type},
        async:false,
        cache:false,
        timeout:120000,
        dataType:'text',
        success: function(data){
            var pos = data.indexOf('{"');
            data = data.slice(pos);
            data = eval('(' + data + ')');
            htmlcontent = data;
        },
        error: function(err){
            htmlcontent = {status:2,message:err,html:''};
        }
    });
    return htmlcontent;
}

function tips(str){
    var timestap = new Date();
    timestap = timestap.Format("yyyy-MM-dd hh:mm:ss");
    $('#tips').empty();
    if(str.length <= 0){
        $('#tips').empty();
    }else{
        $('#tips').prepend('<p>'+timestap+' '+str+'</p>');
    }
}

Date.prototype.Format = function (fmt) { //author: meizz 
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "h+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}