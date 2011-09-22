select * from `webpdv`.`wms_requisicao`
select * from `webpdv`.`ikeda_requisicao`

delete from `webpdv`.`wms_requisicao` where id < 5


update `webpdv`.`wms_requisicao` set status = 'erro' where id < 7
update `webpdv`.`wms_requisicao` set metodo = 'get', parametros = '{"id":364}' where id = 7


desc webpdv.pessoa

select table_name from information_schema.tables where table_schema = 'webpdv' and table_name like 'pessoa%'
desc webpdv.pessoas_enderecos;
desc webpdv.pessoas_fisicas

