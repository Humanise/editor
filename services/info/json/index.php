<?php
require_once '../../../Editor/Include/Public.php';

Response::sendObject([
  'date' => SystemInfo::getDate(),
  'templates' => [
    'installed' => TemplateService::getInstalledTemplateKeys(),
    'used' => TemplateService::getUsedTemplates()
  ],
  'tools' => [
    'installed' => ToolService::getInstalled()
  ],
  'email' => [
    'enabled' => MailService::getEnabled(),
    'server' => Strings::isNotBlank(MailService::getServer()),
    'username' => Strings::isNotBlank(MailService::getUsername()),
    'password' => Strings::isNotBlank(MailService::getPassword()),
    'standardEmail' => Strings::isNotBlank(MailService::getStandardEmail()),
    'standardName' => Strings::isNotBlank(MailService::getStandardName()),
    'feedbackEmail' => Strings::isNotBlank(MailService::getFeedbackEmail()),
    'feedbackName' => Strings::isNotBlank(MailService::getFeedbackName())
  ],
  'inspection' => InspectionService::getStatus()
]);
?>