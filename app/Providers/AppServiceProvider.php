<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Maatwebsite\Excel\Writer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerForProd();
    }

    /**
     * packeagesのクラスをbindする
     *
     * @return void
     */
    private function registerForProd()
    {
     
        #MediaAppData
        $this->app->bind(
            \packages\MediaAppData\UseCase\Create\CreateMediaAppDataServiceInterface::class,
            \packages\MediaAppData\UseCase\Create\CreateMediaAppDataService::class
        );
        $this->app->bind(
            \packages\MediaAppData\Infrastructure\GetAppData\GetAppDataRepositoryInterface::class,
            \packages\MediaAppData\Infrastructure\GetAppData\GetAppDataRepository::class
        );

        #Consumer
        $this->app->bind(
            \packages\Consumer\UseCase\Read\GetCsDataServiceInterface::class,
            \packages\Consumer\UseCase\Read\GetCsDataService::class
        );
        $this->app->bind(
            \packages\Consumer\UseCase\Create\CreateConsumerDataServiceInterface::class,
            \packages\Consumer\UseCase\Create\CreateConsumerDataService::class
        );
        $this->app->bind(
            \packages\Consumer\UseCase\Update\UpdateConsumerDataServiceInterface::class,
            \packages\Consumer\UseCase\Update\UpdateConsumerDataService::class
        );

        $this->app->bind(
            \packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface::class,
            \packages\Consumer\UseCase\Validate\ValidateConsumerDataService::class
        );
        $this->app->bind(
            \packages\Consumer\UseCase\Validate\CheckDuplicateConsumerServiceInterface::class,
            \packages\Consumer\UseCase\Validate\CheckDuplicateConsumerService::class
        );

        $this->app->bind(
            \packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface::class,
            \packages\Consumer\Infrastructure\Consumer\ConsumerRepository::class
        );
    
        #Download
        $this->app->bind(
            \packages\Download\UseCase\Create\CreateCsDataDownloadServiceInterface::class,
            \packages\Download\UseCase\Create\CreateCsDataDownloadService::class
        );

        #Job
        $this->app->bind(
            \packages\Job\Infrastructure\JobMapping\JobMappingRepositoryInterface::class,
            \packages\Job\Infrastructure\JobMapping\JobMappingRepository::class
        );
        $this->app->bind(
            \packages\Job\UseCase\Read\GetJobKeywordServiceInterface::class,
            \packages\Job\UseCase\Read\GetJobKeywordService::class
        );
        $this->app->bind(
            \packages\Job\UseCase\Read\CheckKeywordServiceInterface::class,
            \packages\Job\UseCase\Read\CheckKeywordService::class
        );
        $this->app->bind(
            \packages\Job\UseCase\Update\UpdateJobKeywordServiceInterface::class,
            \packages\Job\UseCase\Update\UpdateJobKeywordService::class
        ); 





        #Lambda
        $this->app->bind(
            \packages\Lambda\UseCase\Request\CommonRequestServiceInterface::class,
            \packages\Lambda\UseCase\Request\CommonRequestService::class
        );
        #Mail
        $this->app->bind(
            \packages\Mail\Infrastructure\MailMessage\MailMessageRepositoryInterface::class,
            \packages\Mail\Infrastructure\MailMessage\MailMessageRepository::class
        );
        $this->app->bind(
            \packages\Mail\Infrastructure\ToCompanyMailMessage\ToCompanyMailMessageRepositoryInterface::class,
            \packages\Mail\Infrastructure\ToCompanyMailMessage\ToCompanyMailMessageRepository::class
        );
        $this->app->bind(
            \packages\Mail\UseCase\Send\MailSendServiceInterface::class,
            \packages\Mail\UseCase\Send\MailSendService::class
        );

        #Resend
        $this->app->bind(
            \packages\Resend\Infrastructure\Resend\ResendRepositoryInterface::class,
            \packages\Resend\Infrastructure\Resend\ResendRepository::class
        );
        $this->app->bind(
            \packages\Resend\UseCase\Register\ResendRegisterServiceInterface::class,
            \packages\Resend\UseCase\Register\ResendRegisterService::class
        );
        $this->app->bind(
            \packages\Resend\UseCase\Update\ResendUpdateServiceInterface::class,
            \packages\Resend\UseCase\Update\ResendUpdateService::class
        );
        $this->app->bind(
            \packages\Resend\UseCase\Read\GetResendServiceInterface::class,
            \packages\Resend\UseCase\Read\GetResendService::class
        );
        $this->app->bind(
            \packages\Resend\UseCase\Validate\CheckResendServiceInterface::class,
            \packages\Resend\UseCase\Validate\CheckResendService::class
        );
        #Line
        $this->app->bind(
            \packages\Line\UseCase\Send\LineSendServiceInterface::class,
            \packages\Line\UseCase\Send\LineSendService::class
        );
        $this->app->bind(
            \packages\Line\Infrastructure\ToCompanyLineMessage\ToCompanyLineMessageRepositoryInterface::class,
            \packages\Line\Infrastructure\ToCompanyLineMessage\ToCompanyLineMessageRepository::class
        );
        $this->app->bind(
            \packages\Line\Infrastructure\LineMessage\LineMessageRepositoryInterface::class,
            \packages\Line\Infrastructure\LineMessage\LineMessageRepository::class
        );
        #Remind
        $this->app->bind(
            \packages\Remind\Infrastructure\Remind\RemindRepositoryInterface::class,
            \packages\Remind\Infrastructure\Remind\RemindRepository::class
        );
        $this->app->bind(
            \packages\Remind\UseCase\Register\RegisterRemindServiceInterface::class,
            \packages\Remind\UseCase\Register\RegisterRemindService::class
        );
        $this->app->bind(
            \packages\Remind\UseCase\Update\UpdateRemindServiceInterface::class,
            \packages\Remind\UseCase\Update\UpdateRemindService::class
        );
        $this->app->bind(
            \packages\Remind\UseCase\Read\ReadRemindServiceInterface::class,
            \packages\Remind\UseCase\Read\ReadRemindService::class
        );



        $this->app->bind(
            \packages\Remind\UseCase\Register\SetMsgRemindServiceInterface::class,
            \packages\Remind\UseCase\Register\SetMsgRemindService::class
        );




        #Url
        $this->app->bind(
            \packages\Url\UseCase\Create\CreateUrlOtherWorksheetServiceInterface::class,
            \packages\Url\UseCase\Create\CreateUrlOtherWorksheetService::class
        );
        $this->app->bind(
            \packages\Url\UseCase\Validate\UrlParamServiceInterface::class,
            \packages\Url\UseCase\Validate\UrlParamService::class
        );

        #Schedule
        $this->app->bind(
            \packages\Schedule\Infrastructure\Schedule\ScheduleRepositoryInterface::class,
            \packages\Schedule\Infrastructure\Schedule\ScheduleRepository::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Create\InsertScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Create\InsertScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Decide\DecideScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Decide\DecideScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Delete\DeleteScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Delete\DeleteScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Shap\ShapScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Shap\ShapScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Read\GetScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Read\GetScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Update\SetScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Update\SetScheduleService::class
        );
        $this->app->bind(
            \packages\Schedule\UseCase\Validate\ValidateScheduleServiceInterface::class,
            \packages\Schedule\UseCase\Validate\ValidateScheduleService::class
        );

        #Spread
        $this->app->bind(
            \packages\Spread\UseCase\Read\GetSpreadDataServiceInterface::class,
            \packages\Spread\UseCase\Read\GetSpreadDataService::class
        );


        #Sms
        $this->app->bind(
            \packages\Sms\UseCase\Send\SmsSendServiceInterface::class,
            \packages\Sms\UseCase\Send\SmsSendService::class
        );

        $this->app->bind(
            \packages\Sms\Infrastructure\SmsMessage\SmsMessageRepositoryInterface::class,
            \packages\Sms\Infrastructure\SmsMessage\SmsMessageRepository::class
        );
        #Text
        $this->app->bind(
            \packages\Text\UseCase\Make\MakeTextServiceInterface::class,
            \packages\Text\UseCase\Make\MakeTextService::class
        );




        #User
        $this->app->bind(
            \packages\User\UseCase\Read\GetUserServiceInterface::class,
            \packages\User\UseCase\Read\GetUserService::class
        );
        $this->app->bind(
            \packages\User\Infrastructure\User\UserRepositoryInterface::class,
            \packages\User\Infrastructure\User\UserRepository::class
        );
        $this->app->bind(
            \packages\User\UseCase\Update\UpdateUserServiceInterface::class,
            \packages\User\UseCase\Update\UpdateUserService::class
        );
        $this->app->bind(
            \packages\User\UseCase\Create\CreateUserDataServiceInterface::class,
            \packages\User\UseCase\Create\CreateUserDataService::class
        );






        #Worksheet
        $this->app->bind(
            \packages\Worksheet\UseCase\Create\InsertWsDataServiceInterface::class,
            \packages\Worksheet\UseCase\Create\InsertWsDataService::class
        );
        $this->app->bind(
            \packages\Worksheet\UseCase\Update\WorksheetUpdateServiceInterface::class,
            \packages\Worksheet\UseCase\Update\WorksheetUpdateService::class
        );
        $this->app->bind(
            \packages\Worksheet\UseCase\Shap\ShapWsDataServiceInterface::class,
            \packages\Worksheet\UseCase\Shap\ShapWsDataService::class
        );
        $this->app->bind(
            \packages\Worksheet\UseCase\Read\GetWsDataServiceInterface::class,
            \packages\Worksheet\UseCase\Read\GetWsDataService::class
        );




        $this->app->bind(
            \packages\Worksheet\UseCase\Validate\CheckPatternServiceInterface::class,
            \packages\Worksheet\UseCase\Validate\CheckPatternService::class
        );





        $this->app->bind(
            \packages\Worksheet\UseCase\Validate\IsAnswerServiceInterface::class,
            \packages\Worksheet\UseCase\Validate\IsAnswerService::class
        );
        $this->app->bind(
            \packages\Worksheet\Infrastructure\Worksheet\WorksheetRepositoryInterface::class,
            \packages\Worksheet\Infrastructure\Worksheet\WorksheetRepository::class
        );

        #BlackList
        $this->app->bind(
            \packages\BlackList\Infrastructure\BlackList\BlackListRepositoryInterface::class,
            \packages\BlackList\Infrastructure\BlackList\BlackListRepository::class
        );
        $this->app->bind(
            \packages\BlackList\UseCase\Delete\DeleteBlackListServiceInterface::class,
            \packages\BlackList\UseCase\Delete\DeleteBlackListService::class
        );
        $this->app->bind(
            \packages\BlackList\UseCase\Create\AddBlackListServiceInterface::class,
            \packages\BlackList\UseCase\Create\AddBlackListService::class
        );
        $this->app->bind(
            \packages\BlackList\UseCase\Read\GetBlackListServiceInterface::class,
            \packages\BlackList\UseCase\Read\GetBlackListService::class
        );
        $this->app->bind(
            \packages\BlackList\UseCase\Validate\CheckBlackListServiceInterface::class,
            \packages\BlackList\UseCase\Validate\CheckBlackListService::class
        );

        



    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Writer::macro('setIncludeCharts', function (Writer $writer, bool $includeChart) {
        //     $writer->setIncludeCharts($includeChart);
        // });
    }
}
