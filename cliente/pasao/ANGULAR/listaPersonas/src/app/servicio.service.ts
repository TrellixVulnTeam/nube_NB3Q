import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ServicioService {

  private p;

  constructor(private http: HttpClient) {
		this.p = this.http.get("http://localhost:80/ajax/APIS/servidor.php?servicio=listar");
  }

  /*peti(){
    console.log("TOY EN MI PETI");
    return this.http.get(this.urlPersonajes);
    // return this.http.get<{res:Object}>(this.urlPersonajes);
  }*/

	//  Defino el método:
	peticion(url, metodo, fRes = null, param = "") {
		metodo = metodo.toUpperCase();
		if (fRes != null) {
			this.p.onreadystatechange = function () {
				if ((this.readyState == 4) && (this.status == 200))
					//console.log(this.responseText);
					fRes(JSON.parse(this.responseText));
			};
		}
		if (metodo == "GET") {
			if (param.trim().length > 0)
				url += "?" + param;
			this.p.open(metodo, url);
			this.p.setRequestHeader("Accept", "application/json; charset=utf-8");
			this.p.send();
		}
		if (metodo == "POST") {
			this.p.open(metodo, url);
			//    this.p.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			this.p.setRequestHeader("Accept", "application/json");
			this.p.setRequestHeader("Content-Type", "application/json; charset=utf-8");
			this.p.send(param);
		}
		if (metodo == "DELETE") {
			this.p.open(metodo, url);
			this.p.setRequestHeader("Accept", "application/json");
			this.p.send();
		}

		if (metodo == "PUT") {
			this.p.open(metodo, url);
			this.p.setRequestHeader("Accept", "application/json");
			this.p.setRequestHeader("Content-Type", "application/json; charset=utf-8");
			this.p.send(param);
		}
	}
}
