#!/usr/bin/python
# -*- coding: utf-8 -*-

'''
INSTRUÇÕES:


Para utilizar basta colocar no seu código a função abaixo apresentada e depois 
invocar a função GenerateMbRef(ent_id, subent_id, order_id, order_value) passando
os respectivos parámetros:

ent_id - Entidade fornecida pela IfthenPay software no acto da realização de contracto
subent_id - Subentidade fornecida pela ifthen software no acto da realização de contracto
order_id - número de identificação do pagamento que pode ser o número de cliente, número de encomenda, etc
order_value - valor a pagar

'''

def GenerateMbRef( ent_id, subent_id, order_id, order_value ):
   chk_val = 0;
   
   order_id = "0000" + order_id
   
   if len(ent_id) != 5:
        print("Lamentamos mas tem de indicar uma entidade válida")
        return;
        
   if len(subent_id) == 0:
        print("Lamentamos mas tem de indicar uma subentidade válida")
        return;
        
   if order_value < 1:
        print("Lamentamos mas não é possível gerar uma referência MB para valores inferiores a 1 Euro")
        return;
   
   order_value = '%01.2f' % order_value
   
   if len(subent_id) == 1:
        #apenas serão considerados os 6 caracteres mais à direita do order_id
        chk_str = ent_id + subent_id + order_id[-6:] + str('%08.0f' % int(round(float(order_value) * 100)))
   elif len(subent_id) == 2:
        #apenas serão considerados os 5 caracteres mais à direita do order_id
        chk_str = ent_id + subent_id + order_id[-5:] + str('%08.0f' % int(round(float(order_value) * 100)))
   else:
        chk_str = ent_id + subent_id + order_id[-4:] + str('%08.0f' % int(round(float(order_value) * 100)))
        
   chk_array = [3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53, 45, 62, 38, 89, 17, 73, 51];
   
   i = len(chk_str)
   
   for chk_item in chk_array:
        chk_val += (int(chk_str[i-1]) % 10) * chk_item
        i -= 1
   
   chk_val %= 97
   
   chk_digits = '%02.0f' % (98-chk_val)
   
   print("Entidade: %s" % ent_id)
   print("Referencia: %s %s %s" % (chk_str[5:5+3], chk_str[8:8+3], chk_str[11:11+1] + chk_digits))
   print("Valor: %s" % order_value)
   
GenerateMbRef("93999", "99", "0404", 14.5)