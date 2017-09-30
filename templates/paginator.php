<?php if ($pageCount > 1):?>
<ul class="pagination-list">  

    <li class="pagination-item pagination-item-prev">
        <?php if ($currentPage !== 1):?>
        <a href="index.php?page=<?=($currentPage - 1)?>">Назад</a>
        <?php else:?>
        <a>Назад</a>
        <?php endif;?>
    </li>

    <?php foreach ($pages as $page):?>
    <li class="pagination-item <?=($page === $currentPage) ? 'pagination-item-active' : '' ;?> ">
        <?php if ($page !== $currentPage):?>
        <a href="index.php?page=<?=$page?>"><?=$page;?></a>
        <?php else:?>
        <a><?=$page;?></a>
        <?php endif;?>
    </li>  
    <?php endforeach;?>  

    <li class="pagination-item pagination-item-next">  
    <?php if ($currentPage !== $pageCount):?>
    <a href="index.php?page=<?=($currentPage + 1)?>">Вперед</a>
     <?php else:?>
    <a>Вперед</a>
     <?php endif;?>  
    </li>

</ul>
<?php endif;?>