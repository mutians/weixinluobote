function getdata(){
    $('#submit').attr('disabled','true');
    $('#submit').addClass('disabled');
    $('#submit span').html('正在搜索，请稍候...');
    var page_size = $('#page_size').val();
    if(page_size.length <= 0){
        page_size = 20;
    }
    var libtype = $('#libtype').val();
    var url = '?act=search&libtype='+libtype+'&page_no=1&page_size='+page_size;
    window.location.href = url;
    return true;
    return false;
}