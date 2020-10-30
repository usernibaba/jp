<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="">
    <button id="btn-prize">开始抽奖</button>
    <script src="/jquery-3.2.1.min.js"></script>
    <script>
            $(document).on('click',"#btn-prize",function(){
                    $.ajax({
                        url:"prize",
                        type:'get',
                        dataType:'json',
                        success:function(d){

                               if(d.errno==400003){
                                   window.location.href='/login/login'
                               }
                               alert(d.msg);
                        }
                    });
            })

    </script>
</form>
</body>
</html>
