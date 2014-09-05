<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UploadAvatarForm extends CFormModel
{
    public $user_id;
    public $fileData;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('user_id, fileData', 'required'),
            array('fileData', 'file', 'types'=>'jpg,jpeg,png'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'user_id'=>'User ID',
            'fileData' => 'File'
        );
    }
}
