<script>
$('#myTab li a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
})

</script>

<div class="mtop35">
    <ul id="#myTab" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#visovi">Вызовы</a></li>
        <li><a data-toggle="tab" href="#master">Мастерская</a></li>
        <li><a data-toggle="tab" href="#upravlenie">Управление</a></li>
    </ul>
</div>