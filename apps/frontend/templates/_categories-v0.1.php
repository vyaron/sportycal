<table border="0px" width="90%" cellspacing="20px">
    <tr>
    <?php for ($i=0; $i < $categoriesCount; $i+=1): ?>

        <td class="tdCategory">
            <a href="<?php echo url_for('category/show?id='.$categories[$i]->getId()) ?>">
                <?php echo image_tag($categories[$i]->getImagePath(), 'align="left" class="imgCtg"')?>    
                <?php echo $categories[$i]->getName() ?>
            </a>
        </td>
        <td class="tdCategoryCals">
                <?php echo $categories[$i]->getCalsCount() . " calendars" ?>
        </td>
        <td class="tdSpacer">
            &nbsp;
        </td>
        <td class="tdCategory">
            <?php if (++$i < $categoriesCount) :?>
                <a href="<?php echo url_for('category/show?id='.$categories[$i]->getId()) ?>">
                    <?php echo image_tag($categories[$i]->getImagePath(), 'align="left" class="imgCtg"')?>    
                    <?php echo $categories[$i]->getName() ?>
                </a>
            <?php else  :?>
                &nbsp;
            <?php endif?>                

        </td>
        <td class="tdCategoryCals">
            <?php if ($i < $categoriesCount) :?>
                <?php echo $categories[$i]->getCalsCount() . " calendars" ?>
            <?php else  :?>
                &nbsp;
            <?php endif?>                

        </td>
    </tr>
    <?php endfor; ?>

</table>