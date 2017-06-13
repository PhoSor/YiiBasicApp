<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ShamanForm;
use yii\httpclient\Client;


class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    /**
     * Displays Shaman page.
     *
     * @return string
     */
    public function actionShaman()
    {
        $model = new ShamanForm();
        $model->load(Yii::$app->request->post());
        if ($model->validate()) {
            
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('post')
                ->setUrl('https://api.shamandev.com/auth/login')
                ->setData(['email' => $model->email, 'password' => $model->password,
                    'curlname' => $model->curlname])
                ->send();
            if ($response->isOk) {
                $model->token = $response->data['token'];
            } else {
                if ($response->data['message']['email']) {
                    $model->errorMessage = $response->data['message']['email'];
                } elseif ($response->data['message']['company']) {
                    $model->errorMessage = $response->data['message']['company'];
                }
            }
            
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl('https://api.shamandev.com/user/current')
                ->addHeaders(['Authorization' => 'Bearer ' . $model->token])
                ->send();
            if ($response->isOk) {
                $model->name = $response->data['name'];
                $model->email = $response->data['email'];
                $model->secretKey = $response->data['secretKey'];
                $model->id = $response->data['id'];
                $model->roleName = $response->data['role']['name'];
            } elseif (!$model->errorMessage && $response->data['message']) {
                $model->errorMessage = $response->data['message'];
            }
        }
        
        return $this->render('shaman', ['model' => $model]);
    }
}
