import { TestBed } from '@angular/core/testing';

import { PAjaxService } from './p-ajax.service';

describe('PAjaxService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: PAjaxService = TestBed.get(PAjaxService);
    expect(service).toBeTruthy();
  });
});
