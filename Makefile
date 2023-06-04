clean-log:
	rm -rf log/*

clean-temp:
	rm -rf temp/*

clean-data:
	rm -rf data/*

clean: clean-log clean-temp
