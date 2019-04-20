<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class SettingService {

  /**
   * @static
   * Sets a setting
   * @param string $domain The domain of the setting (system,tool,template...)
   * @param string $subdomain The domain of the setting (pages,document,mail...)
   * @param string $key The name of the setting
   * @param string $value The value of the setting
   * @param int $user Optinal User ID, defaults to all users (0)
   * @return boolean True on success, False otherwise
   */
  static function setSetting($domain,$subdomain,$key,$value,$user = 0) {
    $sql = "select * from `setting` where " .
      "`domain`=@text(domain) and " .
      "`subdomain`=@text(subdomain) and " .
      "`key`=@text(key) and " .
      "`user_id` = @int(user)";
    if ($row = Database::selectFirst($sql, ['domain' => $domain,'subdomain' => $subdomain, 'key' => $key,'user' => $user])) {
      $sql = "update `setting` set `value`=@text(value) where `id` = @id";
      return Database::update($sql, ['value' => $value, 'id' => $row['id']]);
    } else {
      $sql = [
        'table' => 'setting',
        'values' => [
          'domain' => ['text' => $domain],
          'subdomain' => ['text' => $subdomain],
          'key' => ['text' => $key],
          'value' => ['text' => $value],
          'user_id' => ['int' => $user]
        ]
      ];
      return (Database::insert($sql) !== false);
    }
  }

  static function setServiceSetting($service,$key,$value) {
    SettingService::setSetting('service',$service,$key,$value,InternalSession::getUserId());
  }

  static function getServiceSetting($service,$key) {
    return SettingService::getSetting('service',$service,$key,InternalSession::getUserId());
  }

  /**
   * @static
   */
  static function getSetting($domain,$subdomain,$key,$user = 0) {
    $sql = "select `value` from `setting` where " .
      "`domain`=@text(domain) and " .
      "`subdomain`=@text(subdomain) and " .
      "`key`=@text(key) and " .
      "`user_id` = @int(user)";
    if ($row = Database::selectFirst($sql, ['domain' => $domain,'subdomain' => $subdomain, 'key' => $key,'user' => $user])) {
      return $row['value'];
    } else {
      return NULL;
    }
  }

  static function getOnlineObjectsUrl() {
    return SettingService::getSetting('system','onlineobjects','url');
  }

  static function setOnlineObjectsUrl($value) {
    return SettingService::setSetting('system','onlineobjects','url',$value);
  }

  static function getSharedSecret() {
    return SettingService::getSetting('system','security','sharedsecret');
  }

  static function setSharedSecret($value) {
    return SettingService::setSetting('system','security','sharedsecret',$value);
  }

  static function getLatestHeartBeat() {
    return SettingService::getSetting('system','heartbeat','latest');
  }

  static function setLatestHeartBeat($value) {
    return SettingService::setSetting('system','heartbeat','latest',$value);
  }
}
?>