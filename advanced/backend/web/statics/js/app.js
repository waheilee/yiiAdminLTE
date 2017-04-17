var app = {
    deleteData: function (a) {
        swal({
            title: "确定删除吗?",
            text: "此操作不可复原!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "确定删除",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'delete',
                url: a.href,
                dataType: 'json',
                success: function (data) {
                    if (data.state) {
                        $.pjax.reload('#item-data-list');
                        swal("已删除", data.message, "success");
                    } else {
                        swal("异常", data.message, "error");
                    }
                },
                error: function (err) {
                    console.log(err);
                    throw err;
                }
            });
        });
    },
    changeStatus: function (a) {
        swal({
            title: "确定切换吗?",
            text: "此操作可复原!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            confirmButtonText: "确定切换",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'get',
                url: a.href,
                dataType: 'json',
                success: function (data) {
                    if (data.state) {
                        $.pjax.reload('#item-data-list');
                        swal("已切换", data.message, "success");
                    } else {
                        swal("异常", data.message, "error");
                    }
                },
                error: function (err) {
                    console.log(err);
                    throw err;
                }
            });
        });
    },
    uploadAvatar: function (file) {
        var fileReader = new FileReader();
        var fileType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
        if (file.files.length === 0) {
            return false;
        }
        if (!fileType.test(file.files[0].type)) {
            $.Notification.autoHideNotify('error', 'top right', '提示信息', '你必须上传一张正方形图片.');
            return;
        }
        fileReader.onload = function (e) {
            var data = e.target.result;
            //加载图片获取图片真实宽度和高度
            var image = new Image();
            image.onload = function (ev) {
                if(image.width != image.height){
                    $.Notification.autoHideNotify('error', 'top right', '提示信息', '你必须上传一张正方形图片.');
                }else{
                    $.ajax({
                        type: 'post',
                        url: '/personal/upload-avatar',
                        dataType: 'json',
                        data: {file: ev.target.src.replace(/^data:image\/\w+;base64,/, ""), type: 'avatar'},
                        success: function (json) {
                            if (json.state) {
                                $('.avatar').attr('src', json.path);
                                $("input[name='avatar']").val(json.path);
                            } else {
                                $.Notification.autoHideNotify('error', 'top right', '提示信息', json.message);
                            }
                        },
                        error: function (err) {
                            console.log(err);
                            throw err;
                        }
                    });
                }
            }
            image.src = data;
        };

        fileReader.readAsDataURL(file.files[0]);
    }

}



