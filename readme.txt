**********************************************************************
 A DEMO PROJECT FOR USER LOGIN AND FETCH ORDER DETAILS
    @author Yashpal <yashpalchhajer@gmail.com>
*********************************************************************

Api Name
1. LOGIN
Method : POST
Type : JSON
Response : JSON
Parameters : 
            1. action : 'login',
            2. user_name : 'user',
            3. password : 'password'
Response    :
        on success 
                responseCode : 0,
                responseMsg  : Logged in success,
                responseData : JSON 
                                username : '',
                                usertype : '',
                                token    : ''
In this Password very on your mysql security configuration if it is on medium then password must contains 
atleast min. length of 8, with 1 Uppercase,1 Lowercase,1 Digit and 1 special character.
Request Obj.
{
	"action" : "login",
	"user_name" : "userAdmin",
	"password" : "User@1234"
}
Response.
{
  "responseCode": 1,
  "responseMsg": "Logged in success",
  "responseData": {
    "username": "userAdmin",
    "usertype": "U",
    "token": "5bb60c8410f50e3b7e711cedd2c09517"
  }
}

2. Track
in which user can get details of order by order id or by date.
Method : POST,
Type : JSON,
Response : JSON,

Headers :
    user_name : 'user_name',
    token : 'token' # that is in response of login

Parameters :
in this user can fetch according to order id or date
For ORDER :
    action : 'track',
    order : order_id
For Date :
    action : 'track',
    date : 'YYYY-MM-DD'

Request :
Order Wise

{
	"action" : "track",
	"order" : "101303"
}


Response :

{
  "responseCode": 0,
  "responseMsg": "",
  "responseData": [
    {
      "order_id": "101303",
      "order_date": "2018-07-14 14:40:06",
      "customer_id": "2",
      "customer_name": "User",
      "customer_mobile": "7052632569",
      "details": [
        {
          "item_name": "Veg Thali",
          "quantity": "1",
          "unit_price": "190"
        },
        {
          "item_name": "Cocklate Shake",
          "quantity": "1",
          "unit_price": "70"
        },
        {
          "item_name": "Fruit Cream",
          "quantity": "2",
          "unit_price": "75"
        }
      ]
    }
  ]
}

Date Wise :
Request :
{
	"action" : "track",
	"date" : "2018-07-14"
}

Response :

{
  "responseCode": 0,
  "responseMsg": "",
  "responseData": [
    {
      "order_id": "101301",
      "order_date": "2018-07-14 14:40:06",
      "customer_id": "1",
      "customer_name": "Test",
      "customer_mobile": "9509801562",
      "details": [
        {
          "item_name": "Veg Thali",
          "quantity": "1",
          "unit_price": "190"
        }
      ]
    },
    {
      "order_id": "101302",
      "order_date": "2018-07-14 14:40:06",
      "customer_id": "1",
      "customer_name": "Test",
      "customer_mobile": "9509801562",
      "details": [
        {
          "item_name": "Chocklate Shake",
          "quantity": "1",
          "unit_price": "70"
        }
      ]
    },
    {
      "order_id": "101303",
      "order_date": "2018-07-14 14:40:06",
      "customer_id": "2",
      "customer_name": "User",
      "customer_mobile": "7052632569",
      "details": [
        {
          "item_name": "Veg Thali",
          "quantity": "1",
          "unit_price": "190"
        },
        {
          "item_name": "Cocklate Shake",
          "quantity": "1",
          "unit_price": "70"
        },
        {
          "item_name": "Fruit Cream",
          "quantity": "2",
          "unit_price": "75"
        }
      ]
    }
  ]
}





