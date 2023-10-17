<?php

namespace app\models\Lib\Forms;

use app\models\Lib\Model;
use app\tables\Contact;
use Yii;


/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $phone;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [[ 'email', 'subject', 'body'], 'required'],
            ['phone', 'string',  'length' => 12],
            [['email'], 'unique', 'targetAttribute' => 'email', 'targetClass' => Contact::class,
                'message' => 'This {attribute} is already exists',
                'when' => function ($model) {

                    if (Contact::find()->where(['email' => $model->email])->exists()) {
                        return true;
                    }
                    return false;

                }

            ],

            // email has to be a valid email address
            ['email', 'email'],
            [['phone'], 'match', 'pattern' =>'/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
//            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha']
        ];
    }

    public function __construct(Contact $contact)
    {
        parent::__construct($contact);
        $this->setAttributes($this->_entity->getAttributes(), false);

    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact()
    {

        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo([$this->email])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();

        return true;
    }

    public function save()
    {

        if (!$this->validate()) {
            return false;
        }

        /** @var Contact $contact */
        $contact = $this->_entity;
        $contact->name = $this->name;
        $contact->email = $this->email;
        $contact->subject = $this->subject;
        $contact->body = $this->body;
        $contact->phone=$this->phone;
        if ($contact->save()) {
            return $this->contact();
        }
        return false;
    }

}
