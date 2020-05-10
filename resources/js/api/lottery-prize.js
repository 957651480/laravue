import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'lottery/prize',
    method: 'get',
    params: query,
  });
}

export function fetchLotteryPrize(id) {
  return request({
    url: 'lottery/prize/detail/' + id,
    method: 'get',
  });
}


export function createLotteryPrize(data) {
  return request({
    url: 'lottery/prize/create',
    method: 'post',
    data,
  });
}

export function updateLotteryPrize(id,data) {
  return request({
    url: 'lottery/prize/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteLotteryPrize(id) {
  return request({
    url: 'lottery/prize/delete/' + id,
    method: 'get',
  });
}
