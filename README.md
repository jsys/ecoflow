# API Ecoflow

Pilotage du matériel Ecoflow **sans aucune dépendance externe**. Ce fichier est un exemple de base pour s'interfacer avec l'API Ecoflow.

Vous devez créer un compte ici https://developer-eu.ecoflow.com/us/ pour créer un accessKey et un secretKey.

Il vous faut renseigner ces infos dans le fichier ecoflow.php puis il vous suffit d'héberger ce fichier sur un serveur PHP ou une box domotique comme Jeedom.

Vous pouvez :
  * lister les périphériques,
  * afficher toutes les infos d'un périphérique
  * définir la puissance de sortie d'un powerstream
  
Vous pouvez étendre les possibilités en vous référant à la documentation ici https://developer-eu.ecoflow.com/us/document/introduction

## Lister les périphériques

Par défaut la liste des périphériques est listée. 

```json
{
code: "0",
message: "Success",
data: [
{
sn: "HW51ZOH400000000",
online: 0,
productName: "PowerStream"
},
{
sn: "P2EBZ7X00000000",
online: 0,
productName: "RIVER Pro"
}
],
eagleEyeTraceId: "ea1a2a5c2c17223695115605617d0000",
tid: ""
}
```

## Information d'un périphérique

Si vous ajoutez un parametre sn=HW513000SF767194 par exemple, tous les paramêtres du périphérique sont affichés.

### Pour un  PowerStream

Doc : https://developer-eu.ecoflow.com/us/document/powerStreamMicroInverter

``` json
{
code: "0",
message: "Success",
data: {
20_1.pv2Temp: 330,
20_1.invOutputWatts: 0,
20_1.pv2RelayStatus: 0,
20_1.mqttTlsLastErr: 0,
20_1.batInputVolt: 4,
20_1.invDemandWatts: 6000,
20_1.wifiEncryptMode: 3,
20_1.pv2OpVolt: 0,
20_1.consNum: 0,
20_1.invOnOff: 1,
20_1.invOpVolt: 2317,
20_1.installCountry: 18002,
20_1.batErrorInvLoadLimit: 8000,
20_1.acSetWatts: 8000,
20_1.feedProtect: 1,
20_1.espTempsensor: 38,
20_1.mqttSockErrno: 0,
20_1.historyInvOutputWatts: 0,
20_1.pvToInvWatts: 0,
20_1.pv1ErrCode: 256,
20_1.invLoadLimitFlag: 8,
20_1.batLoadLimitFlag: 0,
20_1.geneNum: 1,
20_1.invToPlugWatts: 0,
20_134.task10: {
taskIndex: 9,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task11: {
taskIndex: 10,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_1.meshId: 41894602,
20_1.batOpVolt: 148,
20_1.permanentWatts: 6000,
20_1.bpType: 0,
20_1.llcWarningCode: 0,
20_1.invOutputCur: 10,
20_1.lowerLimit: 0,
20_1.mqttTlsStackErr: 0,
20_1.llcStatue: 1,
20_1.pv1WarnCode: 0,
20_1.batOutputLoadLimit: 6000,
20_1.batWarningCode: 0,
20_1.acOffFlag: 1,
20_1.mqttErrTime: 0,
20_1.batInputWatts: 0,
20_1.pv2Statue: 7,
20_1.staIpAddr: -1056855872,
20_1.uwlowLightFlag: 0,
20_1.consWatt: 0,
20_1.fisoRxyz: -162175.36,
20_1.batErrCode: 64,
20_1.meshLayel: 1,
2_1: { },
20_1.dsgRemainTime: 5999,
2_2: { },
20_1.batSoc: 0,
20_1.historyBatInputWatts: 0,
20_1.invBrightness: 306,
20_134.task7: {
taskIndex: 6,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_1.pv2ErrCode: 256,
20_1.invWarnCode: 0,
20_134.task6: {
taskIndex: 5,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_1.parentMac: 2960491986,
20_134.task9: {
taskIndex: 8,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task8: {
taskIndex: 7,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task3: {
taskIndex: 2,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task2: {
taskIndex: 1,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task5: {
taskIndex: 4,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task4: {
taskIndex: 3,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_134.task1: {
taskIndex: 0,
type: 0,
timeRange: {
isConfig: false,
timeData: 0,
timeMode: 0,
startTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
stopTime: {
sec: 0,
week: 0,
min: 0,
hour: 0,
month: 0,
year: 0,
day: 0
},
isEnable: false
}
},
20_1.pv2CtrlMpptOffFlag: 0,
20_1.uwloadLimitFlag: 4,
20_1.floadLimitOut: 500,
20_1.pv1Statue: 7,
20_134.updateTime: "2024-07-31 02:42:13",
20_1.pv1InputCur: 0,
20_1.rssiThreshold: -85,
20_1.wifiErrTime: 1721743885,
20_1.pv2InputWatts: 0,
20_1.historyInvToPlugWatts: 0,
20_1.pv2WarningCode: 0,
20_1.pv2InputVolt: 123,
20_1.wifiConnectChannel: 6,
20_1.batSystem: 1,
20_1.wirelessWarnCode: 0,
20_1.invErrCode: 0,
20_1.dynamicWatts: 0,
20_1.batStatue: 1,
20_1.stackMinFree: 59,
20_1.resetCount: 2455,
20_1.mqttLastDisReason: 0,
20_1.uwsocFlag: 0,
20_1.llcOpVolt: 14,
20_1.batTemp: 0,
20_1.upperLimit: 100,
20_1.rssiVariance: 0,
20_1.historyGridConsWatts: 0,
20_1.plugTotalWatts: 0,
20_1.resetReason: 1,
20_1.llcOffFlag: 0,
20_1.invInputVolt: 0,
20_1.stackFree: 78,
20_1.pv1InputVolt: 123,
20_1.invFreq: 499,
20_1.invToOtherWatts: 0,
20_1.heartbeatFrequency: 15,
20_1.selfMac: 871675644,
20_1.chgRemainTime: 5999,
20_1.llcTemp: 330,
20_1.pv1Temp: 330,
20_1.pv2InputCur: 0,
20_1.pv1InputWatts: 0,
20_1.noiseFloor: 0,
20_1.gridConsWatts: 6000,
20_1.historyPlugTotalWatts: 0,
20_1.geneWatt: 0,
20_1.invTemp: 0,
20_1.wirelessErrCode: 0,
20_1.pv1RelayStatus: 0,
20_1.bmsReqChgVol: 0,
20_1.invOutputLoadLimit: 6001,
20_1.interfaceConnFlag: 1,
20_1.llcErrCode: 0,
20_1.invStatue: 2,
20_1.spaceDemandWatts: 6000,
20_1.pv1CtrlMpptOffFlag: 0,
20_1.historyPvToInvWatts: 0,
20_1.bmsReqChgAmp: 0,
20_1.invRelayStatus: 18,
20_1.historyPermanentWatts: 0,
20_1.antiBackFlowFlag: 6000,
20_1.batOffFlag: 0,
20_1.llcInputVolt: 0,
20_1.updateTime: "2024-07-31 03:08:32",
20_1.mqttErr: 0,
20_1.supplyPriority: 1,
20_1.wifiErr: 201,
20_1.ratedPower: 8000,
20_1.batInputCur: 0,
20_1.pv1OpVolt: 0,
20_1.wifiRssi: -87,
20_1.pvPowerLimitAcPower: 5998,
20_1.installTown: 0,
20_1.wifiFirmwareVersion: 11476
},
eagleEyeTraceId: "ea1a2a582317223698698387712d0000",
tid: ""
}
```

### Pour une batterie River PRO

```json
{
code: "0",
message: "Success",
data: {
pd.wattsInSum: 0,
pd.dcInUsedTime: 11649,
pd.model: 1,
bmsMaster.bqSysStatReq: 128,
inv.cfgAcOutVoltage: 230000,
pd.beepState: 1,
inv.cfgAcChgModeFlg: 0,
inv.cfgStandbyMin: 720,
bmsMaster.bmsFault: 0,
pd.remainTime: 5999,
bmsMaster.maxCellTemp: 32,
inv.outputWatts: 0,
pd.typecWatts: 0,
inv.invOutFreq: 0,
pd.carDelayOffMin: 2860,
inv.chargerType: 0,
bmsMaster.sysVer: 33555466,
pd.chgSunPower: 46430,
pd.carTemp: 28,
bmsMaster.soc: 20,
bmsMaster.maxChargeSoc: 100,
pd.ledWatts: 0,
inv.fanState: 0,
pd.usb1Watts: 0,
pd.standByMode: 120,
bmsMaster.errCode: 0,
inv.inTemp: 32,
inv.cfgAcXboost: 1,
bmsMaster.maxMosTemp: 30,
pd.dsgPowerAC: 1201572,
inv.outTemp: 30,
pd.chgPowerAC: 27089,
bmsMaster.maxCellVol: 3859,
inv.acAutoOutConfig: 255,
inv.invOutVol: 0,
pd.bmsSlave: 0,
bmsMaster.minMosTemp: 30,
pd.sysVer: 17040154,
inv.inputWatts: 0,
inv.invInFreq: 0,
bmsMaster.fullCap: 14013,
pd.carUsedTime: 629432,
pd.soc: 20,
pd.typecTemp: 28,
inv.invOutAmp: 0,
bmsMaster.cycles: 28,
inv.cfgFanMode: 1,
bmsMaster.remainCap: 2706,
inv.dcInVol: 584,
bmsMaster.temp: 32,
pd.chgPowerDC: 28020,
pd.dsgPowerDC: 58654,
pd.ledState: 0,
pd.usb3Watts: 0,
inv.dcInAmp: 0,
pd.typecUsedTime: 404067,
inv.cfgAcOutFreq: 1,
bmsMaster.vol: 30863,
inv.errCode: 0,
pd.usbqcUsedTime: 201328,
bmsMaster.tagChgVol: 33450,
bmsMaster.amp: -101,
pd.wattsOutSum: 0,
pd.carSwitch: 0,
pd.usbUsedTime: 91754,
pd.mpptUsedTime: 463279,
bmsMaster.openBmsIdx: 1,
bmsMaster.minCellTemp: 30,
inv.cfgAcEnabled: 0,
bmsMaster.tagChgAmp: 20000,
inv.invType: 8,
pd.errCode: 0,
inv.invInAmp: 0,
bmsMaster.minCellVol: 3836,
pd.carWatts: 0,
pd.usb2Watts: 0,
pd.invUsedTime: 1551673,
inv.invInVol: 0,
inv.sysVer: 17039636
},
eagleEyeTraceId: "ea1a2a582317223697244638141d0000",
tid: ""
}
```
