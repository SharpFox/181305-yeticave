<?php if ($isAuth): ?> 
<div class="user-menu__image">
    <img src="<?=$userAvatar; ?>" width="40" height="40" alt="Пользователь">
</div>
<div class="user-menu__logged">
    <p><?=$userName; ?></p>
    <a href="logout.php">Выйти</a>
</div>    
<?php else: ?> 
<ul class="user-menu__list">
    <li class="user-menu__item">
        <a href="#">Регистрация</a>
    </li>
    <li class="user-menu__item">
        <a href="login.php">Вход</a>
    </li>
</ul>
<?php endif; ?>