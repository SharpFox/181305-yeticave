<?php if ($pageCount > 1):?>
<ul class="pagination-list">  

    <li class="pagination-item pagination-item-prev">
        <?php if ($currentPage !== 1):?>
        <a href="<?=$scriptPHPName?>.php?page=<?=($currentPage - 1)?><?=isset($linkParametrs) ? implode('', $linkParametrs) : ''?>">Назад</a>
        <?php else:?>
        <a>Назад</a>
        <?php endif;?>
    </li>

    <?php foreach ($pages as $page):?>
    <li class="pagination-item <?=($page === $currentPage) ? 'pagination-item-active' : '' ;?> ">
        <?php if ($page !== $currentPage):?>
        <a href="<?=$scriptPHPName?>.php?page=<?=$page?><?=isset($linkParametrs) ? implode('', $linkParametrs) : ''?>"><?=$page;?></a>
        <?php else:?>
        <a><?=$page;?></a>
        <?php endif;?>
    </li>  
    <?php endforeach;?>  

    <li class="pagination-item pagination-item-next">  
    <?php if ($currentPage !== $pageCount):?>
    <a href="<?=$scriptPHPName?>.php?page=<?=($currentPage + 1)?><?=isset($linkParametrs) ? implode('', $linkParametrs) : ''?>">Вперед</a>
     <?php else:?>
    <a>Вперед</a>
     <?php endif;?>  
    </li>

</ul>
<?php endif;?>