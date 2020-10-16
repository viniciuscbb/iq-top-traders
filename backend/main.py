from iqoptionapi.stable_api import IQ_Option
from iqoptionapi.constants import ACTIVES
from threading import Thread
from datetime import *
from dateutil import tz
import time, pymysql

#### Parâmetros Filtro Ranking #####
pais = "Worldwide"
####################################

#### Parâmetros Busca Entradas #####
tipoD = 'live-deal-digital-option' # Digital
tipoB = 'live-deal-binary-option-placed' # Binária
####################################

#### Parâmetro Gerais IQ Option ####
API = IQ_Option('email', 'senhA')
API.connect()
API.change_balance("PRACTICE") # PRACTICE / REAL

if API.check_connect() == False:
	print("Erro ao se conectar")
	API.connect()
else:
	print("Conectado com sucesso")
		
####################################

#### Função Converte Timestamp2 #####
def converterTimestamp(x, y, z):
	timestamp1, ms1 = divmod(x, 1000)
	timestamp2, ms2 = divmod(y, 1000)
	timestamp3, ms3 = divmod(z, 1000)

	entradacodt = datetime.fromtimestamp(timestamp1) + timedelta(milliseconds=ms1)
	expiracaodt = datetime.fromtimestamp(timestamp2) + timedelta(milliseconds=ms2)
	horaatualdt = datetime.fromtimestamp(timestamp3) + timedelta(milliseconds=ms3)

	entradaco = entradacodt.strftime('%Y-%m-%d %H:%M:%S')
	expiracao = expiracaodt.strftime('%H:%M:%S %d/%m/%Y')
	horaatual = horaatualdt.strftime('%H:%M:%S %d/%m/%Y')

	mintime1 = timedelta(milliseconds=x)
	mintime2 = timedelta(milliseconds=y)	
	mintime3 = timedelta(milliseconds=z)
	min1 = mintime1.seconds
	min2 = mintime2.seconds	
	min3 = mintime3.seconds	

	exptime = min2 - min1
	delaytime = min3 - min1                         
	expminutes = (exptime % 3600) // 60   
	if expminutes == 0:
		expminutes = 1                       


	return [entradaco, expiracao, horaatual, expminutes, delaytime]
####################################

######## Função Buy Normal #########
def normalIQ(pa, st, dr, tf, tp, idTrade, nome, pais, hora):
	connection = pymysql.connect(host='localhost', user='root', password='', db='iqtt', charset='utf8mb4', cursorclass=pymysql.cursors.DictCursor)
	try:
		if tp == "digital":
			status, id = API.buy_digital_spot(pa, 2, dr, tf)

			while True:
				status, r = API.check_win_digital_v2(id)

				if status:
					if r > 0:
						r = 'WIN'
					elif r == 0:
						r = 'EQUAL'
					elif r < 0:
						r = 'LOSS'
				
					try:
						print(f'| Salvando... {tp} | {r} | {nome}')
						with connection.cursor() as cursor:
							sql = f"INSERT INTO operacoes (id, nome, pais, paridade, opcao, expiracao, direcao, valor, hora, resultado) VALUES ({int(idTrade)}, '{str(nome)}', '{str(pais)}', '{str(pa)}', '{str(tp)}', {int(tf)}, '{str(dr)}', {float(st)}, '{hora}', '{str(r)}')"
							cursor.execute(sql)
						connection.commit()
					except:
						connection.rollback()
						print('Erro ao salvar no banco de dados!')
					finally:
						connection.close()
						return 0

		elif tp == "binaria":
			status,id = API.buy(2, pa, dr, tf)

			if status:
				r = API.check_win_v3(id)

				if r:
					if r > 0:
						r = 'WIN'
					elif r == 0:
						r = 'EQUAL'
					elif r < 0:
						r = 'LOSS'
					
					try:
						print(f'| Salvando... {tp} | {r} | {nome}.')
						with connection.cursor() as cursor:
							sql = f"INSERT INTO operacoes (id, nome, pais, paridade, opcao, expiracao, direcao, valor, hora, resultado) VALUES ({int(idTrade)}, '{str(nome)}', '{str(pais)}', '{str(pa)}', '{str(tp)}', {int(tf)}, '{str(dr)}', {float(st)}, '{hora}', '{str(r)}')"
							cursor.execute(sql)
						connection.commit()
					except:
						connection.rollback()
						print('Erro ao salvar no banco de dados!')
					finally:
						connection.close()	
						return 0
	except:
		return 0					
### Função de Análise da Entrada ###
def ajustesEntradaDigital(ti):
	trades = API.get_live_deal(ti)
	print("Analisando "+str(len(trades))+" jogadas!")

	for trade in list(trades):
		entradacopy = trade['created_at']	
		expiracao = trade['instrument_expiration']	 
		horalocal = int(datetime.now(tz=timezone.utc).timestamp() * 1000)
		tmf = (1 if trade['expiration_type'] == 'PT1M' else (5 if trade['expiration_type'] == 'PT5M' else (15 if trade['expiration_type'] == 'PT15M' else "1")))

		timecopy = converterTimestamp(entradacopy, expiracao, horalocal)
		try:
			ativo = list(ACTIVES.keys())[list(ACTIVES.values()).index(trade['instrument_active_id'])]
		except:
			break

		if int(timecopy[4]) <= 5:
			tradetipo = "digital"
			print(f"\n{str(trade['name'])} | {str(trade['flag'])} | {str(ativo)} | DIGITAL | {str(trade['instrument_dir'])} | {str(tmf)}M | ${str(trade['amount_enrolled'])}\nHT {str(timecopy[0])} / HL {str(timecopy[2])} | Delay: {str(timecopy[4])} seg")
			
			td = Thread(target=normalIQ, args=(str(ativo), trade['amount_enrolled'], str(trade['instrument_dir']), int(tmf), str(tradetipo), trade['user_id'], trade['name'], trade['flag'], timecopy[0]),  daemon=True)
			td.start()
	trades.clear()	

### Função de Análise da Entrada ###
def ajustesEntradaBinaria(ti):
	trades = API.get_live_deal(ti)
	print("Analisando "+str(len(trades))+" jogadas!")

	for trade in list(trades):
		entradacopy = trade['created_at']	
		expiracao = trade['expiration']
		horalocal = int(datetime.now(tz=timezone.utc).timestamp() * 1000)

		timecopy = converterTimestamp(entradacopy, expiracao, horalocal)
		try:
			ativo = list(ACTIVES.keys())[list(ACTIVES.values()).index(trade['active_id'])]
		except:
			break

		if int(timecopy[4]) <= 5:
			tradetipo = "binaria"
			print(f"\n{str(trade['name'])} | {str(trade['flag'])} | {str(ativo)}| BINÁRIA | {str(trade['direction'])} | {str(timecopy[3])}M | ${str(trade['amount_enrolled'])}\nHT {str(timecopy[0])} / HL {str(timecopy[2])} | Delay: {str(timecopy[4])} seg")
			try:
				tb = Thread(target=normalIQ, args=(str(ativo), trade['amount_enrolled'], str(trade['direction']), int(timecopy[3]), str(tradetipo), trade['user_id'], trade['name'], trade['flag'], timecopy[0]), daemon=True)
				tb.start()
			except:
				break
	trades.clear()	

####### Main Boot IQ Option #######
API.subscribe_live_deal(tipoB, 10)
API.subscribe_live_deal(tipoD, 10)

while True:
	ajustesEntradaBinaria(tipoB)	
	time.sleep(3)
	ajustesEntradaDigital(tipoD)
	time.sleep(3)

API.unscribe_live_deal(tipoD)
API.unscribe_live_deal(tipoB)
####################################
