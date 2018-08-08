<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 21.07.2017
 * Time: 15:45
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\StatisticVirtualServer;
use Illuminate\Support\Facades\Cache;
use App\Traits\RestSuccessResponseTrait;

/**
 * Class TeamSpeakVirtualServerStatisticsController
 * @package Api\Http\Controllers
 */
class TeamSpeakVirtualServerStatisticsController
{
    use RestSuccessResponseTrait;

    /**
     * @var int Уникальный идентификатор сервера
     */
    private $server_id;
    /**
     * @var string Уникальный идентификатор виртуального сервера
     */
    private $uid;

    /**
     * @api {get} /v1/teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/year За год
     * @apiName Get VirtualServer Statistics Year
     * @apiGroup Virtual Server Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по виртуальному серверу за год по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Пользователей онлайн<br/>
     * Средний пинг<br/>
     * Средняя потеря пакетов<br/>
     * <br/>
     * При этом данные усредняются за сутки.
     * @apiSampleRequest /teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/year
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiParam {String} bashe64uid Уникальный идентификатор виртуального сервера (virtualserver_unique_identifier) закодированный в bashe64
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.virtualserver.statistics.year
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
     *            "slot_usage": "200.0000",
     *            "user_online": "6000.0000",
     *            "avg_ping": 10,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:01:46"
     *        },
     *        {
     *            "slot_usage": "100.0000",
     *            "user_online": "5000.0000",
     *            "avg_ping": 20,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-06 20:01:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @param string $uid Уникальный идентификатор виртуального сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Year(int $server_id, string $uid): JsonResponse
    {
        $this->server_id = $server_id;
        $this->uid = base64_decode($uid);

        $data = Cache::remember('VirtualServerStatisticsYearServerID-' . $server_id . '-VirtualServerUID-' . $this->uid, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.VirtualServer.Year')), function () {
            return StatisticVirtualServer::InstanceId($this->server_id)->VirtualServerUID($this->uid)->StatYear()->DayAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/month За месяц
     * @apiName Get VirtualServer Statistics Month
     * @apiGroup Virtual Server Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по виртуальному серверу за месяц по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Пользователей онлайн<br/>
     * Средний пинг<br/>
     * Средняя потеря пакетов<br/>
     * <br/>
     * При этом данные усредняются за час.
     * @apiSampleRequest /teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/month
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiParam {String} bashe64uid Уникальный идентификатор виртуального сервера (virtualserver_unique_identifier) закодированный в bashe64
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.virtualserver.statistics.month
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
     *            "slot_usage": "200.0000",
     *            "user_online": "6000.0000",
     *            "avg_ping": 10,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:01:46"
     *        },
     *        {
     *            "slot_usage": "100.0000",
     *            "user_online": "5000.0000",
     *            "avg_ping": 20,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 21:01:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @param string $uid Уникальный идентификатор виртуального сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Month(int $server_id, string $uid): JsonResponse
    {
        $this->server_id = $server_id;
        $this->uid = base64_decode($uid);

        $data = Cache::remember('VirtualServerStatisticsMonthServerID-' . $server_id . '-VirtualServerUID-' . $this->uid, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.VirtualServer.Month')), function () {
            return StatisticVirtualServer::InstanceId($this->server_id)->VirtualServerUID($this->uid)->StatMonth()->HourAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/week За неделю
     * @apiName Get VirtualServer Statistics Week
     * @apiGroup Virtual Server Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по виртуальному серверу за неделю по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Пользователей онлайн<br/>
     * Средний пинг<br/>
     * Средняя потеря пакетов<br/>
     * <br/>
     * При этом данные усредняются за 30 минут.
     * @apiSampleRequest /teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/week
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiParam {String} bashe64uid Уникальный идентификатор виртуального сервера (virtualserver_unique_identifier) закодированный в bashe64
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.virtualserver.statistics.week
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
     *            "slot_usage": "200.0000",
     *            "user_online": "6000.0000",
     *            "avg_ping": 10,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:01:46"
     *        },
     *        {
     *            "slot_usage": "100.0000",
     *            "user_online": "5000.0000",
     *            "avg_ping": 20,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:30:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @param string $uid Уникальный идентификатор виртуального сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Week(int $server_id, string $uid): JsonResponse
    {
        $this->server_id = $server_id;
        $this->uid = base64_decode($uid);

        $data = Cache::remember('VirtualServerStatisticsWeekServerID-' . $server_id . '-VirtualServerUID-' . $this->uid, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.VirtualServer.Week')), function () {
            return StatisticVirtualServer::InstanceId($this->server_id)->VirtualServerUID($this->uid)->StatWeek()->HalfHourAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/day За сутки
     * @apiName Get VirtualServer Statistics Day
     * @apiGroup Virtual Server Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по виртуальному серверу за сутки по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Пользователей онлайн<br/>
     * Средний пинг<br/>
     * Средняя потеря пакетов<br/>
     * <br/>
     * При этом данные усредняются за 5 минут.
     * @apiSampleRequest /teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/day
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiParam {String} bashe64uid Уникальный идентификатор виртуального сервера (virtualserver_unique_identifier) закодированный в bashe64
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.virtualserver.statistics.day
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
     *            "slot_usage": "200.0000",
     *            "user_online": "6000.0000",
     *            "avg_ping": 10,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:00:46"
     *        },
     *        {
     *            "slot_usage": "100.0000",
     *            "user_online": "5000.0000",
     *            "avg_ping": 20,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:05:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @param string $uid Уникальный идентификатор виртуального сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Day(int $server_id, string $uid): JsonResponse
    {
        $this->server_id = $server_id;
        $this->uid = base64_decode($uid);

        $data = Cache::remember('VirtualServerStatisticsDayServerID-' . $server_id . '-VirtualServerUID-' . $this->uid, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.VirtualServer.Day')), function () {
            return StatisticVirtualServer::InstanceId($this->server_id)->VirtualServerUID($this->uid)->StatDay()->FiveMinutesAvage()->get();
        });

        return $this->jsonResponse($data);
    }
    /**
     * @api {get} /v1/teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/realtime В реальном времени
     * @apiName Get VirtualServer Statistics Realtime
     * @apiGroup Virtual Server Statistics
     * @apiVersion 1.0.0
     * @apiDescription Получить статистику по виртуальному серверу в реальном времени по таким параметрам как: <br/>
     * Использовано слотов<br/>
     * Пользователей онлайн<br/>
     * Средний пинг<br/>
     * Средняя потеря пакетов<br/>
     * <br/>
     * При этом данные не усредняются, но сбор статистики происходит с интервалом в 1 минуту.<br/><br/>
     * Возврашается вся собраная информация за последний час.
     * @apiSampleRequest /teamspeak/instance/:server_id/virtualserver/:bashe64uid/statistics/realtime
     * @apiParam {Number} server_id Уникальный ID TeamSpeak3 инстанса в API.
     * @apiParam {String} bashe64uid Уникальный идентификатор виртуального сервера (virtualserver_unique_identifier) закодированный в bashe64
     * @apiHeader {String} X-token Ваш токен для работы с API.
     * @apiSuccess (Success code 200) {String} status  Всегда содержит значение "success".
     * @apiPermission api.teamspeak.virtualserver.statistics.realtime
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
     *            "slot_usage": "200.0000",
     *            "user_online": "6000.0000",
     *            "avg_ping": 10,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:00:46"
     *        },
     *        {
     *            "slot_usage": "100.0000",
     *            "user_online": "5000.0000",
     *            "avg_ping": 20,
     *            "avg_packetloss": 0,
     *            "created_at": "2017-01-05 20:01:46"
     *        }
     *    ]
     *}
     */
    /**
     * @param int $server_id Уникальный идентификатор сервера
     * @param string $uid Уникальный идентификатор виртуального сервера
     * @return JsonResponse Обьект с данными для ответа
     */
    function Realtime(int $server_id, string $uid): JsonResponse
    {
        $this->server_id = $server_id;
        $this->uid = base64_decode($uid);

        $data = Cache::remember('VirtualServerStatisticsRealtimeServerID-' . $server_id . '-VirtualServerUID-' . $this->uid, Carbon::now()->addMinutes(config('ApiCache.TeamSpeak.Statistics.VirtualServer.Realtime')), function () {
            return StatisticVirtualServer::InstanceId($this->server_id)->VirtualServerUID($this->uid)->StatRealtime()->get();
        });

        return $this->jsonResponse($data);
    }
}