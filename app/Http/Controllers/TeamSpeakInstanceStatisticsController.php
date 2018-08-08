<?php

/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 27.06.2017
 * Time: 17:42
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\StatisticInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\RestSuccessResponseTrait;

/**
 * Class Statistics
 * @package Api\Http\Controllers\TeamSpeak
 */
class TeamSpeakInstanceStatisticsController extends Controller
{
    use RestSuccessResponseTrait;

    /**
     * @var int Уникальный идентификатор сервера
     */
    private $server_id;

    /**
     * @api {get} /v1/teamspeak/instance/:server_id/statistics/year За год
     * @apiName Get Instanse Statistics Year
     * @apiGroup Instance Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по teamspeak3 инстансу (серверу) за год по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Запушено виртуальных серверов<br/>
     * Пользователей онлайн<br/>
     * <br/>
     * При этом данные усредняются за сутки.
     * @apiSampleRequest /teamspeak/instance/:server_id/statistics/year
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.instanse.statistics.year
     * @apiUse INVALID_SERVER_ID
     * @apiUse SOURCE_NOT_AVAILABLE
     * @apiUse FIELD_NOT_SPECIFIED
     * @apiUse INVALID_IP_ADDRESS
     * @apiUse INVALID_TOKEN
     * @apiUse REQUEST_LIMIT_EXCEEDED
     * @apiUse TOKEN_IS_BLOCKED
     * @apiSuccessExample {json} Успешно выполненный запрос:
     *     HTTP/1.1 200 OK
     *{
     *    "status": "success",
     *    "data": [
     *        {
     *            "slot_usage": "4000.0000",
     *            "server_runing": "150.0000",
     *            "user_online": "3000.0000",
     *            "created_at": "2017-01-05 20:01:46"
     *        },
     *        {
     *            "slot_usage": "5000.0000",
     *            "server_runing": "200.0000",
     *            "user_online": "1000.0000",
     *            "created_at": "2017-01-06 20:01:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Year(int $server_id): JsonResponse
    {
        $this->server_id = $server_id;

        $data = Cache::remember('InstanseStatisticsYearServerID-' . $server_id, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.Instanse.Year')), function () {
            return StatisticInstance::InstanceId($this->server_id)->StatYear()->DayAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/statistics/month За месяц
     * @apiName Get Instanse Statistics Month
     * @apiGroup Instance Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по teamspeak3 инстансу (серверу) за месяц по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Запушено виртуальных серверов<br/>
     * Пользователей онлайн<br/>
     * <br/>
     * При этом данные усредняются за час.
     * @apiSampleRequest /teamspeak/instance/:server_id/statistics/month
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.instanse.statistics.month
     * @apiUse INVALID_SERVER_ID
     * @apiUse SOURCE_NOT_AVAILABLE
     * @apiUse FIELD_NOT_SPECIFIED
     * @apiUse INVALID_IP_ADDRESS
     * @apiUse INVALID_TOKEN
     * @apiUse REQUEST_LIMIT_EXCEEDED
     * @apiUse TOKEN_IS_BLOCKED
     * @apiSuccessExample {json} Успешно выполненный запрос:
     *     HTTP/1.1 200 OK
     *{
     *    "status": "success",
     *    "data": [
     *        {
     *            "slot_usage": "4000.0000",
     *            "server_runing": "150.0000",
     *            "user_online": "3000.0000",
     *            "created_at": "2017-01-05 20:01:46"
     *        },
     *        {
     *            "slot_usage": "5000.0000",
     *            "server_runing": "200.0000",
     *            "user_online": "1000.0000",
     *            "created_at": "2017-01-05 21:01:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Month(int $server_id): JsonResponse
    {
        $this->server_id = $server_id;

        $data = Cache::remember('InstanseStatisticsMonthServerID-' . $server_id, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.Instanse.Month')), function () {
            return StatisticInstance::InstanceId($this->server_id)->StatMonth()->HourAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/statistics/week За неделю
     * @apiName Get Instanse Statistics Veek
     * @apiGroup Instance Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по teamspeak3 инстансу (серверу) за неделю по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Запушено виртуальных серверов<br/>
     * Пользователей онлайн<br/>
     * <br/>
     * При этом данные усредняются за 30 минут.
     * @apiSampleRequest /teamspeak/instance/:server_id/statistics/week
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.instanse.statistics.week
     * @apiUse INVALID_SERVER_ID
     * @apiUse SOURCE_NOT_AVAILABLE
     * @apiUse FIELD_NOT_SPECIFIED
     * @apiUse INVALID_IP_ADDRESS
     * @apiUse INVALID_TOKEN
     * @apiUse REQUEST_LIMIT_EXCEEDED
     * @apiUse TOKEN_IS_BLOCKED
     * @apiSuccessExample {json} Успешно выполненный запрос:
     *     HTTP/1.1 200 OK
     *{
     *    "status": "success",
     *    "data": [
     *        {
     *            "slot_usage": "4000.0000",
     *            "server_runing": "150.0000",
     *            "user_online": "3000.0000",
     *            "created_at": "2017-01-05 20:00:00"
     *        },
     *        {
     *            "slot_usage": "5000.0000",
     *            "server_runing": "200.0000",
     *            "user_online": "1000.0000",
     *            "created_at": "2017-01-05 20:30:00"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Week(int $server_id): JsonResponse
    {
        $this->server_id = $server_id;

        $data = Cache::remember('InstanseStatisticsWeekServerID-' . $server_id, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.Instanse.Week')), function () {
            return StatisticInstance::InstanceId($this->server_id)->StatWeek()->HalfHourAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/statistics/day За сутки
     * @apiName Get Instanse Statistics Day
     * @apiGroup Instance Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по teamspeak3 инстансу (серверу) за сутки по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Запушено виртуальных серверов<br/>
     * Пользователей онлайн<br/>
     * <br/>
     * При этом данные усредняются за 5 минут.
     * @apiSampleRequest /teamspeak/instance/:server_id/statistics/day
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.instanse.statistics.day
     * @apiUse INVALID_SERVER_ID
     * @apiUse SOURCE_NOT_AVAILABLE
     * @apiUse FIELD_NOT_SPECIFIED
     * @apiUse INVALID_IP_ADDRESS
     * @apiUse INVALID_TOKEN
     * @apiUse REQUEST_LIMIT_EXCEEDED
     * @apiUse TOKEN_IS_BLOCKED
     * @apiSuccessExample {json} Успешно выполненный запрос:
     *     HTTP/1.1 200 OK
     *{
     *    "status": "success",
     *    "data": [
     *        {
     *            "slot_usage": "4000.0000",
     *            "server_runing": "150.0000",
     *            "user_online": "3000.0000",
     *            "created_at": "2017-01-05 20:00:00"
     *        },
     *        {
     *            "slot_usage": "5000.0000",
     *            "server_runing": "200.0000",
     *            "user_online": "1000.0000",
     *            "created_at": "2017-01-05 20:05:00"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Day(int $server_id): JsonResponse
    {
        $this->server_id = $server_id;

        $data = Cache::remember('InstanseStatisticsDayServerID-' . $server_id, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.Instanse.Day')), function () {
            return StatisticInstance::InstanceId($this->server_id)->StatDay()->FiveMinutesAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/statistics/realtime В реальном времени
     * @apiName Get Instanse Statistics Realtime
     * @apiGroup Instance Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по teamspeak3 инстансу (серверу) в реальном времени по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Запушено виртуальных серверов<br/>
     * Пользователей онлайн<br/>
     * <br/>
     * При этом данные не усредняются, но сбор статистики происходит с интервалом в 1 минуту. <br/><br/>
     * Возврашается вся собраная информация за последний час.
     * @apiSampleRequest /teamspeak/instance/:server_id/statistics/realtime
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.instanse.statistics.realtime
     * @apiUse INVALID_SERVER_ID
     * @apiUse SOURCE_NOT_AVAILABLE
     * @apiUse FIELD_NOT_SPECIFIED
     * @apiUse INVALID_IP_ADDRESS
     * @apiUse INVALID_TOKEN
     * @apiUse REQUEST_LIMIT_EXCEEDED
     * @apiUse TOKEN_IS_BLOCKED
     * @apiSuccessExample {json} Успешно выполненный запрос:
     *     HTTP/1.1 200 OK
     *{
     *    "status": "success",
     *    "data": [
     *        {
     *            "slot_usage": "4000.0000",
     *            "server_runing": "150.0000",
     *            "user_online": "3000.0000",
     *            "created_at": "2017-01-05 20:00:00"
     *        },
     *        {
     *            "slot_usage": "5000.0000",
     *            "server_runing": "200.0000",
     *            "user_online": "1000.0000",
     *            "created_at": "2017-01-05 20:01:00"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Realtime(int $server_id): JsonResponse
    {
        $this->server_id = $server_id;

        $data = Cache::remember('InstanseStatisticsRealtimeServerID-' . $server_id, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.Instanse.Realtime')), function () {
            return StatisticInstance::InstanceId($this->server_id)->StatRealtime()->get();
        });

        return $this->jsonResponse($data);
    }

}