import { TestBed } from '@angular/core/testing';

import { PetsAndVisitService } from './pets-and-visit.service';

describe('PetsAndVisitService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: PetsAndVisitService = TestBed.get(PetsAndVisitService);
    expect(service).toBeTruthy();
  });
});
