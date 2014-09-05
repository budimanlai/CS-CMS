<div class="form-group">
    <label class="control-label col-lg-2" for="Content_Thumb_Image"><?php echo $label; ?></label>
    <div class="col-lg-10">
        <div style="margin-bottom: 5px;overflow: hidden; display: block; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px">
            <img style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px" id="<?php echo $avatarPreview?>" src="<?php echo $image_url?>" alt="<?php echo CHtml::encode($label); ?>"/>
        </div>
        <button type="button" id="<?php echo $btnId; ?>" class="btn btn-primary">Change</button>
        <button type="button" id="<?php echo $btnApply; ?>" class="btn btn-primary" style="display: none;">Apply</button>
        <div id="<?php echo $cropContainer; ?>" style="display: none; margin-top: 5px; padding: 10px; border: 1px solid #cacaca;">
            <span>Click and drag on the image to select an area.</span>
            <div id="<?php echo $cropContainer."_area"; ?>"></div>
        </div>
        <span id="<?php echo $helper?>" class="help-block">Ukuran gambar adalah <?php echo Yii::app()->params['user']['avatar_size']; ?>. Format gambar yang boleh diupload adalah jpg, jpeg atau png.</span>
    </div>
</div>