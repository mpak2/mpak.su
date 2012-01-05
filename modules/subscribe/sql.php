<? die;

//DROP TABLE IF EXISTS `test`.`mp_subscribe_index`;
//CREATE TABLE `test`.`mp_subscribe_index` SELECT * FROM `shop_mpak_su`.`mp_business_index` WHERE (description LIKE "%строитель%") LIMIT 10000

// DROP TABLE IF EXISTS `argo_mpak_su`.`mp_subscribe_index`;
// CREATE TABLE `argo_mpak_su`.`mp_subscribe_index` SELECT * FROM `shop_mpak_su`.`mp_subscribe_zakazrf` WHERE (addr LIKE "%ленинград%" OR addr LIKE "%петербург%") LIMIT 10000

// CREATE TABLE `argo_mpak_su`.`mp_subscribe_index` SELECT * FROM `shop_mpak_su`.`mp_subscribe_ya_kredit` LIMIT 11000
// WHERE (addr LIKE "%ленинград%" OR addr LIKE "%петербург%")

//CREATE TABLE `argo_mpak_su`.`mp_subscribe_index` SELECT * FROM `shop_mpak_su`.`mp_subscribe_index` WHERE (addr LIKE "%ленинград%" OR addr LIKE "%петербург%") LIMIT 11000
//CREATE TABLE `argo_mpak_su`.`mp_subscribe_index` SELECT * FROM `shop_mpak_su`.`mp_subscribe_index` WHERE (addr LIKE "%ленинград%" OR addr LIKE "%петербург%" OR addr LIKE "%Москва%") LIMIT 11000
?>