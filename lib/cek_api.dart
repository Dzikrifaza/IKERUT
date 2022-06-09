import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/cupertino.dart';
import 'package:http/http.dart' as http;

class CekApi extends StatelessWidget {
  const CekApi({
    Key key,
  }) : super(key: key);

  final String cek = "http://192.168.43.145:8000/api/produk";
  Future getProduct() async {
    var response = await http.get(Uri.parse(cek));
    print(json.decode(response.body));
    return json.decode(response.body);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Asu'),
      ),
      body: FutureBuilder(
        future: getProduct(),
        builder: (context, snapshot) {
          if (snapshot.hasData) {
            return ListView.builder(
                itemCount: snapshot.data['data'].length,
                itemBuilder: (context, index) {
                  return Container(
                    height: 180,
                    child: Card(
                      elevation: 10,
                      child: Row(children: [
                        Image.network(cek +
                            "/" +
                            snapshot.data['data'][index]['thumbnail']),
                      ]),
                    ),
                  );
                  // snapshot.data['data'][index]['thumbnail'].toString());
                });
          } else {
            return Text('00');
          }
        },
      ),
    );
  }
}
