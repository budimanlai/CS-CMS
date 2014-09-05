<?php $this->beginContent('//layouts/main'); ?>
<div class="box">
    <?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
        )); ?><!-- breadcrumbs -->
    <?php endif?>
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>