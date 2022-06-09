// ignore_for_file: prefer_const_constructors, prefer_const_literals_to_create_immutables

import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:ikerut/detail/produkdetailpage.dart';
import 'package:ikerut/konstant.dart';
import 'package:ikerut/model/produk.dart';
import 'package:http/http.dart' as http;
import 'package:ikerut/selengkapnya_page.dart';

class WidgetByKategori extends StatefulWidget {
  final Widget child;
  final int id;
  final String kategori;
  final int warna;

  const WidgetByKategori(this.id, this.kategori, this.warna,
      {Key key, this.child})
      : super(key: key);

  @override
  _WidgetByKategoriState createState() => _WidgetByKategoriState();
}

class _WidgetByKategoriState extends State<WidgetByKategori> {
  List _get = [];

  final String cek = "c";
  // Future getProduct() async {
  //   var response = await http.get(Uri.parse(cek));
  //   print(json.decode(response.body));
  //   return json.decode(response.body);
  // }
  // Future _getData() async {
  //   try {
  //     final response = await http.get(Uri.parse(iUrl));
  //     // cek apakah respon berhasil
  //       final data = json.decode(response.body);
  //       print(data);
  //     if (response.statusCode == 200) {
  //       setState(() {
  //         //memasukan data yang di dapat dari internet ke variabel _get
  //         _get = data['kategori'];
  //       });
  //     }
  //   } catch (e) {
  //     //tampilkan error di terminal
  //     print(e);
  //   }
  // }

  List<Produk> produkList = [];

  Future<List<Produk>> fetchProduk() async {
    List<Produk> usersList;
    try {
      var response = await http.get(Uri.parse(cek));
      if (response.statusCode == 200) {
        final items = json.decode(response.body).cast<Map<String, dynamic>>();
        usersList = items.map<Produk>((json) {
          return Produk.fromJson(json);
        }).toList();
        setState(() {
          produkList = usersList;
        });
      }
    } catch (e) {
      usersList = produkList;
    }
    print(usersList);
    return usersList;
  }

  @override
  Widget build(BuildContext context) {
    // getProduct();
    return Container(
      margin: EdgeInsets.only(bottom: 20),
      padding: EdgeInsets.only(right: 10),
      color: Colors.white,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(
            margin: EdgeInsets.only(bottom: 10, top: 10),
            padding: EdgeInsets.only(right: 10),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  child: Text(
                    widget.kategori,
                    style: TextStyle(color: Colors.white),
                  ),
                  width: 200,
                  padding:
                      EdgeInsets.only(left: 10, right: 10, top: 2, bottom: 2),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.only(
                      topRight: Radius.circular(10),
                      bottomRight: Radius.circular(10),
                    ),
                    color: Color.fromARGB(255, 159, 94, 238),
                    boxShadow: [
                      BoxShadow(
                        color: Color.fromARGB(255, 159, 94, 238),
                        spreadRadius: 1,
                      ),
                    ],
                  ),
                ),
                InkWell(
                  onTap: () {
                    Navigator.of(context).push(MaterialPageRoute(
                        builder: (context) => SelengkapnyaPage(
                              title: widget.kategori,
                              id: widget.id.toString(),
                              ids: "",
                            )));
                  },
                  child: Text(
                    "Selengkapnya",
                    style: TextStyle(
                      color: Color.fromARGB(255, 159, 94, 238),
                    ),
                  ),
                ),
              ],
            ),
          ),
          Container(
            height: 200,
            margin: EdgeInsets.only(bottom: 7),
            child: widget.id == null
                ? CircularProgressIndicator()
                : FutureBuilder<List<Produk>>(
                    future: fetchProduk(),
                    builder: (context, snapshot) {
                      if (!snapshot.hasData) {
                        return Center(child: CircularProgressIndicator());
                      }
                      return ListView.builder(
                        scrollDirection: Axis.horizontal,
                        itemCount: snapshot.data.length,
                        itemBuilder: (context, i) => Card(
                          child: InkWell(
                            onTap: () {
                              Navigator.of(context).push(
                                MaterialPageRoute<void>(
                                  builder: (context) => ProdukDetailPage(
                                    snapshot.data[i].id,
                                    snapshot.data[i].judul,
                                    snapshot.data[i].harga,
                                    snapshot.data[i].hargax,
                                    snapshot.data[i].thumbnail,
                                    snapshot.data[i].deskripsi,
                                    false,
                                    snapshot.data[i].satuan,
                                  ),
                                ),
                              );
                            },
                            child: SizedBox(
                              width: MediaQuery.of(context).size.width / 2,
                              child: Column(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  SizedBox(
                                    height: 110,
                                    width: 170,
                                    child: Image.network(
                                      cek + "/" + snapshot.data[i].thumbnail,
                                      fit: BoxFit.fill,
                                    ),
                                  ),
                                  Container(
                                      padding: EdgeInsets.only(left: 10),
                                      alignment: Alignment.topLeft,
                                      child: Text(snapshot.data[i].judul)),
                                  Padding(
                                    padding: const EdgeInsets.only(
                                        bottom: 8, right: 20),
                                    child: Row(
                                      mainAxisAlignment: MainAxisAlignment.end,
                                      children: [
                                        Text(
                                          snapshot.data[i].harga,
                                          style: TextStyle(color: Colors.red),
                                        ),
                                        Text("/" + snapshot.data[i].satuan),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
