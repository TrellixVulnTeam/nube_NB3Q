import { Owner } from './owner';
import { Visit } from './visit';
import { Pettype } from './pettype';

export interface Pet {
    id:number;
    name:string;
    birth_date:Date;
    type:Pettype;
    owner:Owner;
    visits:Visit[];
}
