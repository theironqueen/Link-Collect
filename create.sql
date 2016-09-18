
drop table if exists linkTimes;
drop table if exists link;
drop table if exists tab;
drop table if exists record;
/*==============================================================*/
/* Table: tab                                                   */
/*==============================================================*/
create table tab
(
   tab_id               int not null AUTO_INCREMENT,
   tab_parent_id        int,
   tab_name             varchar(20) not null,
   tab_time             datetime not null,
   primary key (tab_id)
);


alter table tab add constraint FK_tabToTab foreign key (tab_parent_id)
      references tab (tab_id) on delete CASCADE on update CASCADE ;

/*==============================================================*/
/* Table: link                                                  */
/*==============================================================*/
create table link
(
   link_id              int not null AUTO_INCREMENT,
   tab_id               int,
   link_name            varchar(20) not null,
   link_time            datetime not null,
   link_address         varchar(1024) not null,
   primary key (link_id)
);

alter table link add constraint FK_tabToLink foreign key (tab_id)
      references tab (tab_id) on delete CASCADE on update CASCADE;

/*==============================================================*/
/* Table: linkTimes                                             */
/*==============================================================*/
create table linkTimes
(
   link_id              int,
   click_time           datetime not null
);

alter table linkTimes add constraint FK_linkToTimes foreign key (link_id)
      references link (link_id) on delete CASCADE on update CASCADE;

/*==============================================================*/
/* Table: record                                                */
/*==============================================================*/
create table record
(
   record_id            int not null AUTO_INCREMENT,
   record_type          int not null,
   record_time          datetime not null,
   record_memo          text not null,
   record_object        int not null,
   primary key (record_id)
);