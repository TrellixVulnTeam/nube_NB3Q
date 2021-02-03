import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class OwnersService {

  private url: string = "http://localhost/cliente/provinciaslocalidades/serviciosWeb/petclinic/servicios.php"

  constructor(private http: HttpClient) { }

  getOwners(){
    let pa= JSON.stringify({
      accion: "ListarOwners"
    });

    return this.http.post<any[]>(this.url, pa);
  }

  getOwnerId(){
    let pa= JSON.stringify({
      accion: "ObtenerOwnersId"
    });

    return this.http.post<any[]>(this.url, pa);
  }
}
