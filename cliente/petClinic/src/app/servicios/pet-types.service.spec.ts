import { TestBed } from '@angular/core/testing';

import { PetTypesService } from './pet-types.service';

describe('PetTypesService', () => {
  let service: PetTypesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PetTypesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
