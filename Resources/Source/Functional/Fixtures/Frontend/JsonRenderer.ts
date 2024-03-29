config; {
	no_cache = 1;
	debug = 0;
	xhtml_cleaning = 0;
	admPanel = 0;
	disableAllHeaderCode = 1;
	sendCacheHeaders = 0;
	sys_language_uid = 0;
	sys_language_mode = ignore;
	sys_language_overlay = 1;
	additionalHeaders;.10.header = Content-Type;: application/json; charset=utf-8;
	additionalHeaders;.10.replace = 1;

	watcher; {
		tableFields; {
			pages = uid,_PAGES_OVERLAY_UID,pid,sorting,title,tx_irretutorial_hotels;
			sys_category = uid,_ORIG_uid,_LOCALIZED_UID,pid,sys_language_uid,title,parent,items,sys_language_uid;
			sys_file = uid,_ORIG_uid,_LOCALIZED_UID,pid,title,sys_language_uid;
			sys_file_reference = uid,_ORIG_uid,_LOCALIZED_UID,title,description,alternative,link,missing,identifier,file,pid,sys_language_uid,title,parent,items,sys_language_uid,uid_local,uid_foreign,tablenames,fieldname,table_local;
			tt_content = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,header,categories,tx_irretutorial_1nff_hotels;
			tx_irretutorial_1nff_hotel = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,offers;
			tx_irretutorial_1nff_offer = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,prices;
			tx_irretutorial_1nff_price = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,price;
			tx_irretutorial_1ncsv_hotel = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,offers;
			tx_irretutorial_1ncsv_offer = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,prices;
			tx_irretutorial_1ncsv_price = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title,price;
			tx_testdatahandler_element = uid,_ORIG_uid,_LOCALIZED_UID,pid,sorting,sys_language_uid,title
		}
	}
}

lib.watcherDataObject = COA;
lib.watcherDataObject; {
	1 = LOAD_REGISTER;
	1.;watcher.dataWrap =; |
	2 = USER;
	2.;userFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Collector->addRecordData;
	99 = RESTORE_REGISTER
}

lib.watcherFileObject = COA;
lib.watcherFileObject; {
	1 = LOAD_REGISTER;
	1.;watcher.dataWrap =; |
	2 = USER;
	2.;userFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Collector->addFileData;
	99 = RESTORE_REGISTER
}

page = PAGE;
page; {
	10 = COA;
	10; {
		1 = LOAD_REGISTER;
		1.;watcher.dataWrap = pages;:{uid}
		2 = USER;
		2.;userFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Collector->addRecordData;
		10 = CONTENT;
		10; {
			stdWrap.required = 1;
			table = pages;
			select; {
				orderBy = sorting;
				pidInList = this;
				# prevent; sys_language_uid; lookup;
				languageField = 0
			}
			renderObj < lib.watcherDataObject;
			renderObj;.1.watcher.dataWrap = {register:watcher}|;.__pages/pages;:{uid}
		}
		15 = CONTENT;
		15; {
			if.isTrue.field = tx_irretutorial_hotels;
			table = tx_irretutorial_1nff_hotel;
			select; {
				orderBy = sorting;
				where.field = uid;
				where.intval = 1;
				where.wrap = parenttable='pages'; AND; parentid=;|
			}
			renderObj < lib.watcherDataObject;
			renderObj;.1.watcher.dataWrap = {register:watcher}|;.tx_irretutorial_hotels/tx_irretutorial_1nff_hotel;:{uid}
		}
		20 = CONTENT;
		20; {
			table = tt_content;
			select; {
				orderBy = sorting;
				where = {;#colPos}=0
			}
			renderObj < lib.watcherDataObject;
			renderObj;.1.watcher.dataWrap = {register:watcher}|;.__contents/tt_content;:{uid}
			renderObj; {
				10 = CONTENT;
				10; {
					if.isTrue.field = categories;
					table = sys_category;
					select; {
						pidInList = root,-1;
						selectFields = sys_category.*
						join = sys_category_record_mm; ON; sys_category_record_mm.uid_local = sys_category.uid;
						where.data = field;:_ORIG_uid; // field:_LOCALIZED_UID // field:uid
						where.intval = 1;
						where.wrap = sys_category_record_mm.uid_foreign=;|
						orderBy = sys_category_record_mm.sorting_foreign;
						languageField = sys_category.sys_language_uid
					}
					renderObj < lib.watcherDataObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.categories/sys_category;:{uid}
				}
				20 = CONTENT;
				20; {
					if.isTrue.field = tx_irretutorial_1nff_hotels;
					table = tx_irretutorial_1nff_hotel;
					select; {
						orderBy = sorting;
						where.field = uid;
						where.intval = 1;
						where.wrap = parenttable='tt_content'; AND; parentid=;|
					}
					renderObj < lib.watcherDataObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.tx_irretutorial_1nff_hotels/tx_irretutorial_1nff_hotel;:{uid}
					renderObj; {
						10 = CONTENT;
						10; {
							if.isTrue.field = offers;
							table = tx_irretutorial_1nff_offer;
							select; {
								orderBy = sorting;
								where.field = uid;
								where.intval = 1;
								where.wrap = parenttable='tx_irretutorial_1nff_hotel'; AND; parentid=;|
							}
							renderObj < lib.watcherDataObject;
							renderObj;.1.watcher.dataWrap = {register:watcher}|;.offers/tx_irretutorial_1nff_offer;:{uid}
							renderObj; {
								10 = CONTENT;
								10; {
									if.isTrue.field = prices;
									table = tx_irretutorial_1nff_price;
									select; {
										orderBy = sorting;
										where.field = uid;
										where.intval = 1;
										where.wrap = parenttable='tx_irretutorial_1nff_offer'; AND; parentid=;|
									}
									renderObj < lib.watcherDataObject;
									renderObj;.1.watcher.dataWrap = {register:watcher}|;.prices/tx_irretutorial_1nff_price;:{uid}
								}
							}
						}
					}
				}
				30 = CONTENT;
				30; {
					if.isTrue.field = tx_irretutorial_1ncsv_hotels;
					table = tx_irretutorial_1ncsv_hotel;
					select; {
						uidInList.data = field;:tx_irretutorial_1ncsv_hotels;
						orderBy = sorting;
						# prevent; sys_language_uid; lookup;
						languageField = 0
					}
					renderObj < lib.watcherDataObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.tx_irretutorial_1ncsv_hotels/tx_irretutorial_1ncsv_hotel;:{uid}
					renderObj; {
						10 = CONTENT;
						10; {
							if.isTrue.field = offers;
							table = tx_irretutorial_1ncsv_offer;
							select; {
								uidInList.data = field;:offers;
								orderBy = sorting;
								# prevent; sys_language_uid; lookup;
								languageField = 0
							}
							renderObj < lib.watcherDataObject;
							renderObj;.1.watcher.dataWrap = {register:watcher}|;.offers/tx_irretutorial_1ncsv_offer;:{uid}
							renderObj; {
								10 = CONTENT;
								10; {
									if.isTrue.field = prices;
									table = tx_irretutorial_1ncsv_price;
									select; {
										uidInList.data = field;:prices;
										orderBy = sorting;
										# prevent; sys_language_uid; lookup;
										languageField = 0
									}
									renderObj < lib.watcherDataObject;
									renderObj;.1.watcher.dataWrap = {register:watcher}|;.prices/tx_irretutorial_1ncsv_price;:{uid}
								}
							}
						}
					}
				}
				40 = FILES;
				40; {
					if.isTrue.field = image;
					references; {
						fieldName = image
					}
					renderObj < lib.watcherFileObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.image/
				}
				50 = CONTENT;
				50; {
					if.isTrue.field = tx_testdatahandler_select;
					table = tx_testdatahandler_element;
					select; {
						uidInList.data = field;:tx_testdatahandler_select;
						pidInList = 0;
						orderBy = sorting;
						# prevent; sys_language_uid; lookup;
						languageField = 0
					}
					renderObj < lib.watcherDataObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.tx_testdatahandler_select/tx_testdatahandler_element;:{uid}
				}
				60 = CONTENT;
				60; {
					if.isTrue.field = tx_testdatahandler_group;
					table = tx_testdatahandler_element;
					select; {
						uidInList.data = field;:tx_testdatahandler_group;
						pidInList = 0;
						orderBy = sorting;
						# prevent; sys_language_uid; lookup;
						languageField = 0
					}
					renderObj < lib.watcherDataObject;
					renderObj;.1.watcher.dataWrap = {register:watcher}|;.tx_testdatahandler_group/tx_testdatahandler_element;:{uid}
				}
			}
		}
		stdWrap.postUserFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Collector->attachSection;
		stdWrap.postUserFunc.as = Default
	}
	99999 = COA;
	99999; {
		stdWrap.postUserFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Renderer->renderValues;
		stdWrap.postUserFunc.values; {
			page.children; {
				uid.data = page;:uid;
				pid.data = page;:pid;
				title.data = page;:title
			}
			tsfe.children; {
				sys_language_uid.data = tsfe;:sys_language_uid;
				sys_language_mode.data = tsfe;:sys_language_mode;
				sys_language_content.data = tsfe;:sys_language_content;
				sys_language_contentOL.data = tsfe;:sys_language_contentOL
			}
		}
		stdWrap.postUserFunc.as = Scope
	}
	stdWrap.postUserFunc = TYPO3;\TestingFramework;\Core;\Functional;\Framework;\Frontend;\Renderer->renderSections
}

[globalVar = GP;:L = 1;]
config.sys_language_uid = 1;
[end]
[globalVar = GP;:L = 2;]
config.sys_language_uid = 2;
[end]
[globalVar = GP;:L = 3;]
config.sys_language_uid = 3;
[end];
