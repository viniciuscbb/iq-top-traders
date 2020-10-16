from flask import Flask, request
from iqoptionapi.stable_api import IQ_Option
from datetime import datetime, timedelta
import time
app = Flask("IQTT")
API = IQ_Option('email', 'senha8')

API.connect()
while True:
    if API.check_connect() == False:
        print('>> Erro ao se conectar!\n')
    else:
        print('>> Conectado com sucesso!\n')
        break

def ctimeframe(timeframe):
    if timeframe == 'M1':
        return 1
    elif timeframe == 'M5':
        return 5
    elif timeframe == 'M10':
        return 10
    elif timeframe == 'M15':
        return 15
    elif timeframe == 'M30':
        return 30
    else:
        return 'error'

@app.route("/lista", methods=["POST"])
def recebeJSON():
    body = request.get_json()

    if("nome" not in body):
        return geraResponse(400, "O parametro nome é obrigatorio", 0, 0, 0)

    if("data" not in body):
        return geraResponse(400, "O parametro data é obrigatorio", 0, 0, 0)

    if("gale" not in body):
        return geraResponse(400, "O parametro gale é obrigatorio", 0, 0, 0)

    if("sinais" not in body):
        return geraResponse(400, "O parametro sinais é obrigatorio", 0, 0, 0)

    json = body["nome"], body["data"], body["gale"], body["sinais"]
    result, wins, loss = checar(json)

    return geraResponse(200, json, result, wins, loss)


def geraResponse(status, json, result, wins, loss):
    response = {}
    if status == 400:
        response["status"] = status
        response["mensagem"] = json
    else:
        response["status"] = status
        response["nome"] = json[0]
        response["data"] = json[1]
        response["gale"] = json[2]
        response["wins"] = wins
        response["loss"] = loss
        response["sinais"] = result
    return response

def checar(json):
    wins = 0
    loss = 0
    nome = json[0]
    data = json[1]
    qtdGale = json[2]
    sinais = json[3]
    resultado = []
    print(f'\n >>> Analisando lista {nome}')
    sinal = sinais.split('\r\n')

    if not sinal[len(sinal)-1]:
        sinal.pop(len(sinal)-1)

    for x in sinal:
        c = 0
        x2 = x
        x = x.split(';')
        timeframe = ctimeframe(x[0])
        par = x[1]
        hora = x[2]
        direcao = x[3]

        s = f'{data} {hora}:00'
        tempo = time.mktime(datetime.strptime(
            s, '%Y-%m-%d %H:%M:%S').timetuple())
        velas = API.get_candles(par, (timeframe * 60), 1, tempo)
        result = 'PUT' if velas[0]['open'] > velas[0]['close'] else 'CALL'

        if direcao == result:
            deu = 'WIN'
            wins += 1
        else:
            for i in range(qtdGale):
                c = c + 1
                if c > 1:
                    tempo = datetime.fromtimestamp(tempo)
                    tempo = tempo + timedelta(minutes=timeframe)
                    tempo = time.mktime(datetime.strptime(
                        str(tempo), '%Y-%m-%d %H:%M:%S').timetuple())
                else:
                    tempo = datetime.strptime(s, '%Y-%m-%d %H:%M:%S')
                    tempo = tempo + timedelta(minutes=timeframe)
                    tempo = time.mktime(datetime.strptime(
                        str(tempo), '%Y-%m-%d %H:%M:%S').timetuple())

                velas = API.get_candles(par, (timeframe * 60), 1, tempo)
                result = 'PUT' if velas[0]['open'] > velas[0]['close'] else 'CALL'
                if direcao == result:
                    deu = f'WIN {c} GALE'
                    wins += 1
                    break
                elif c >= qtdGale:
                    deu = 'LOSS'
                    loss +=1
                    break
        resultado.append(f'{x2} {deu}')
        print(f'{x2} {deu}')
    print(f'WINS: {wins} | LOSS: {loss}')
    return resultado, wins, loss

app.run(debug=False, threaded=False)
