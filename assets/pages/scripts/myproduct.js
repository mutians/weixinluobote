function getdata(){
    $('#submit').attr('disabled','true');
    $('#submit').addClass('disabled');
    $('#submit span').html('正在搜索，请稍候...');
    var page_size = $('#page_size').val();
    if(page_size.length <= 0){
        page_size = 20;
    }
    var platform = $('#platform').val();
    var favorites_id = $('#favorites_id').val();
    var url = '?act=search&platform='+platform+'&favorites_id='+favorites_id+'&page_no=1&page_size='+page_size;
    window.location.href = url;
    return true;
    return false;
}