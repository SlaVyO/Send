$( document ).ready(function() {
    $("#regbtn").click(
		function(){
            var  niknameValue=$('input.nikname').val();
            var  emailValue=$('input.email').val();
            var  passwrdValue=$('input.passwrd').val();
            var  passwrd_cnfValue=$('input.passwrd_cnf').val();
            var  doposVal='OK';
            //добавить отправку нажатия дупост
        jQuery.ajax({
        url:     "registr.php", //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: {nikname: niknameValue, email: emailValue, passwrd: passwrdValue, passwrd_cnf: passwrd_cnfValue, dopos:doposVal,},  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
           result = $.parseJSON(response);//jQuery.parseJSON(response);
           if (result.status=="Error")
           {
           $('#result_form').html(result.error);
            }
            else if (result.status=="OK")
           {
            $('#regform').css('display', 'none');
           $('#result_form').html('Регистрация прошла успешно</br>Форма активации отправленна на указнный вами адресс</br><a href="/send/start.php">На главнаю</a>');
            }
        },
        error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
       }
    });
}
          
); });
 
