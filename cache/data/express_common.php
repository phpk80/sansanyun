<?php 
 if (!defined('in_sansan')) {exit('Access Denied');}
$sansan_express_common=Array('11' => Array('id'=>11,'name'=>'顺丰速递','code'=>'shunfeng','code2'=>'SF','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":647,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/shunfeng.jpg\\",\\"list\\":[{\\"width\\":154,\\"height\\":28,\\"left\\":136,\\"top\\":179,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":315,\\"height\\":59,\\"left\\":123,\\"top\\":209,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":159,\\"height\\":26,\\"left\\":219,\\"top\\":277,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":93,\\"height\\":30,\\"left\\":362,\\"top\\":362,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":340,\\"height\\":67,\\"left\\":113,\\"top\\":393,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":188,\\"height\\":27,\\"left\\":220,\\"top\\":458,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"}]}"','sort'=>1,'addtime'=>1464148741),'5' => Array('id'=>5,'name'=>'圆通快递','code'=>'yuantong','code2'=>'YTO','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":553,\\"background\\":\\".\\/upload\\/img\\/expresstpl\\/yuantong.jpg\\",\\"list\\":[{\\"width\\":172,\\"height\\":31,\\"left\\":558,\\"top\\":117,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":326,\\"height\\":59,\\"left\\":551,\\"top\\":181,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":105,\\"height\\":22,\\"left\\":145,\\"top\\":121,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":318,\\"height\\":50,\\"left\\":131,\\"top\\":183,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":159,\\"height\\":24,\\"left\\":183,\\"top\\":245,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":163,\\"height\\":27,\\"left\\":597,\\"top\\":247,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"}]}"','sort'=>2,'addtime'=>1450089379),'3' => Array('id'=>3,'name'=>'中通快递','code'=>'zhongtong','code2'=>'','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":554,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/zhongtong.jpg\\",\\"list\\":[{\\"width\\":115,\\"height\\":22,\\"left\\":150,\\"top\\":127,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":314,\\"height\\":57,\\"left\\":151,\\"top\\":161,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":246,\\"height\\":23,\\"left\\":152,\\"top\\":257,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":110,\\"height\\":22,\\"left\\":554,\\"top\\":130,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":301,\\"height\\":59,\\"left\\":557,\\"top\\":157,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":156,\\"height\\":29,\\"left\\":558,\\"top\\":256,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"}]}"','sort'=>3,'addtime'=>1450089225),'12' => Array('id'=>12,'name'=>'百世汇通','code'=>'huitongkuaidi','code2'=>'','status'=>1,'express_type'=>1,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":556,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/huitongkuaidi.jpg\\",\\"list\\":[{\\"width\\":125,\\"height\\":19,\\"left\\":149,\\"top\\":112,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":343,\\"height\\":65,\\"left\\":87,\\"top\\":187,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":208,\\"height\\":22,\\"left\\":146,\\"top\\":259,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":124,\\"height\\":17,\\"left\\":562,\\"top\\":109,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":356,\\"height\\":68,\\"left\\":503,\\"top\\":186,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":216,\\"height\\":25,\\"left\\":550,\\"top\\":260,\\"txt\\":\\"{mobile}\\"}]}"','sort'=>100,'addtime'=>1450089432),'10' => Array('id'=>10,'name'=>'如风达快递','code'=>'rufeng','code2'=>'','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":600,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/rufeng.jpg\\",\\"list\\":[{\\"width\\":163,\\"height\\":32,\\"left\\":90,\\"top\\":374,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":268,\\"height\\":25,\\"left\\":43,\\"top\\":498,\\"txt\\":\\"\\u8be6\\u7ec6\\u5730\\u5740{addr}\\"},{\\"width\\":124,\\"height\\":22,\\"left\\":241,\\"top\\":539,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"},{\\"width\\":82,\\"height\\":23,\\"left\\":40,\\"top\\":470,\\"txt\\":\\"\\u7701{province}\\"},{\\"width\\":77,\\"height\\":23,\\"left\\":143,\\"top\\":469,\\"txt\\":\\"\\u5e02(\\u53bf){city}\\"},{\\"width\\":96,\\"height\\":25,\\"left\\":263,\\"top\\":469,\\"txt\\":\\"\\u533a(\\u9547){town}\\"},{\\"width\\":124,\\"height\\":29,\\"left\\":81,\\"top\\":123,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":150,\\"height\\":23,\\"left\\":65,\\"top\\":287,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":341,\\"height\\":24,\\"left\\":30,\\"top\\":253,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"}]}"','sort'=>100,'addtime'=>1450175358),'9' => Array('id'=>9,'name'=>'天天快递','code'=>'tiantian','code2'=>'','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":557,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/tiantian.jpg\\",\\"list\\":[{\\"width\\":118,\\"height\\":25,\\"left\\":170,\\"top\\":143,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":330,\\"height\\":58,\\"left\\":131,\\"top\\":218,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":109,\\"height\\":24,\\"left\\":164,\\"top\\":284,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":131,\\"height\\":26,\\"left\\":569,\\"top\\":145,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":107,\\"height\\":30,\\"left\\":576,\\"top\\":281,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"},{\\"width\\":331,\\"height\\":59,\\"left\\":553,\\"top\\":212,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"}]}"','sort'=>100,'addtime'=>1450089699),'7' => Array('id'=>7,'name'=>'EMS邮政','code'=>'ems','code2'=>'','status'=>1,'express_type'=>1,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":556,\\"background\\":\\".\\/upload\\/img\\/expresstpl\\/ems.jpg\\",\\"list\\":[{\\"width\\":106,\\"height\\":24,\\"left\\":137,\\"top\\":121,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":129,\\"height\\":25,\\"left\\":317,\\"top\\":117,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":343,\\"height\\":43,\\"left\\":129,\\"top\\":173,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":108,\\"height\\":27,\\"left\\":140,\\"top\\":253,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":156,\\"height\\":18,\\"left\\":319,\\"top\\":252,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"},{\\"width\\":370,\\"height\\":65,\\"left\\":127,\\"top\\":306,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"}]}"','sort'=>100,'addtime'=>1450089549),'6' => Array('id'=>6,'name'=>'韵达快运','code'=>'yunda','code2'=>'','status'=>1,'express_type'=>2,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":551,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/yunda.jpg\\",\\"list\\":[{\\"width\\":136,\\"height\\":35,\\"left\\":154,\\"top\\":118,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":287,\\"height\\":57,\\"left\\":153,\\"top\\":185,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":149,\\"height\\":24,\\"left\\":291,\\"top\\":248,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":90,\\"height\\":26,\\"left\\":536,\\"top\\":119,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":340,\\"height\\":61,\\"left\\":527,\\"top\\":182,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":170,\\"height\\":21,\\"left\\":700,\\"top\\":250,\\"txt\\":\\"\\u624b\\u673a{mobile}\\"}]}"','sort'=>100,'addtime'=>1450089481),'4' => Array('id'=>4,'name'=>'申通快递','code'=>'shentong','code2'=>'','status'=>1,'express_type'=>1,'logo'=>'','insure'=>'0.00','tpl'=>'"{\\"width\\":1000,\\"height\\":557,\\"background\\":\\"\\/upload\\/img\\/expresstpl\\/shentong.jpg\\",\\"list\\":[{\\"width\\":110,\\"height\\":28,\\"left\\":158,\\"top\\":120,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba{send_name}\\"},{\\"width\\":309,\\"height\\":69,\\"left\\":144,\\"top\\":199,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u5b8c\\u6574\\u5730\\u5740{send_address}\\"},{\\"width\\":164,\\"height\\":23,\\"left\\":183,\\"top\\":278,\\"txt\\":\\"\\u5bc4\\u4ef6\\u4eba\\u624b\\u673a{send_mobile}\\"},{\\"width\\":116,\\"height\\":28,\\"left\\":581,\\"top\\":122,\\"txt\\":\\"\\u59d3\\u540d{name}\\"},{\\"width\\":330,\\"height\\":74,\\"left\\":560,\\"top\\":198,\\"txt\\":\\"\\u5b8c\\u6574\\u5730\\u5740{address}\\"},{\\"width\\":217,\\"height\\":27,\\"left\\":625,\\"top\\":277,\\"txt\\":\\"{mobile}\\"}]}"','sort'=>100,'addtime'=>1450089335));